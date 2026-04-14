import os
import io
import numpy as np
import tensorflow as tf
import gdown
from flask import Flask, request, jsonify
from flask_cors import CORS
from PIL import Image

GDRIVE_FOLDER_ID = os.environ.get('GDRIVE_FOLDER_ID', '1sA0uPUO0fQB4XtgztKydGArUyPSzGYgZ')

app = Flask(__name__)
CORS(app)

# ─── KONFIGURASI MODEL ───────────────────────────────────────────────────────
# Folder SavedModel yang ada di dalam folder durian_api
MODEL_PATH = 'model_durian_tf'

# Label kelas — HARUS urut sesuai training (lihat label mapping di notebook):
# {'bawor': 0, 'blackthorn': 1, 'jenis_lainnya': 2, 'musang_king': 3}
CLASS_NAMES = ['bawor', 'blackthorn', 'jenis_lainnya', 'musang_king']


# ─── AUTO DOWNLOAD MODEL ─────────────────────────────────────────────────────
def download_model():
    if os.path.exists(MODEL_PATH) and os.listdir(MODEL_PATH):
        # Cek apakah saved_model.pb benar-benar ada langsung di MODEL_PATH
        if os.path.exists(os.path.join(MODEL_PATH, 'saved_model.pb')):
            print("[OK] Model sudah ada, skip download.")
            return True
        else:
            # Kemungkinan ada subfolder hasil gdown, coba pindahkan
            print("[INFO] Struktur folder tidak sesuai, mencoba memperbaiki...")

    if not GDRIVE_FOLDER_ID:
        print("[GAGAL] GDRIVE_FOLDER_ID tidak diset!")
        return False

    print("Mendownload model dari Google Drive...")
    try:
        TEMP_PATH = 'model_durian_tf_temp'

        # Download ke folder temp dulu
        gdown.download_folder(
            id=GDRIVE_FOLDER_ID,
            output=TEMP_PATH,
            quiet=False,
            use_cookies=False
        )

        # gdown mungkin membuat TEMP_PATH/model_durian_tf/ atau langsung TEMP_PATH/
        # Cari di mana saved_model.pb berada
        import shutil

        pb_langsung = os.path.join(TEMP_PATH, 'saved_model.pb')
        pb_subfolder = os.path.join(TEMP_PATH, 'model_durian_tf', 'saved_model.pb')

        if os.path.exists(pb_langsung):
            # File langsung di TEMP_PATH, pindah ke MODEL_PATH
            if os.path.exists(MODEL_PATH):
                shutil.rmtree(MODEL_PATH)
            shutil.move(TEMP_PATH, MODEL_PATH)

        elif os.path.exists(pb_subfolder):
            # Ada subfolder, angkat isinya naik satu level
            src = os.path.join(TEMP_PATH, 'model_durian_tf')
            if os.path.exists(MODEL_PATH):
                shutil.rmtree(MODEL_PATH)
            shutil.move(src, MODEL_PATH)
            shutil.rmtree(TEMP_PATH, ignore_errors=True)

        else:
            print(f"[GAGAL] saved_model.pb tidak ditemukan setelah download.")
            print(f"[DEBUG] Isi {TEMP_PATH}: {os.listdir(TEMP_PATH)}")
            return False

        print("[OK] Download dan ekstraksi selesai!")
        print(f"[OK] Isi model: {os.listdir(MODEL_PATH)}")
        return True

    except Exception as e:
        print(f"[GAGAL] Download folder gagal: {e}")
        return False
    
    # Panggil download dulu sebelum load
if not download_model():
    print("[GAGAL] Model tidak berhasil didownload!")

print("=" * 55)
print("DurianFy AI Server — Memuat model InceptionV3 V3...")
# ─── LOAD MODEL ──────────────────────────────────────────────────────────────

print("=" * 55)
print("DurianFy AI Server — Memuat model InceptionV3 V3...")
print("=" * 55)

try:
    model_layer = tf.keras.layers.TFSMLayer(MODEL_PATH, call_endpoint='serving_default')
    # Lakukan dummy inference sekali agar model ter-warm-up
    dummy = np.zeros((1, 299, 299, 3), dtype=np.float32)
    _ = model_layer(dummy)
    print(f"[OK] Model berhasil dimuat dari '{MODEL_PATH}'")
    print(f"[OK] Warm-up inference selesai — siap menerima request!")
    print("=" * 55)
except Exception as e:
    print(f"[GAGAL] Tidak bisa memuat model: {e}")
    model_layer = None


# ─── FUNGSI PREPROCESSING ────────────────────────────────────────────────────
def preprocess_image(image_bytes: bytes) -> np.ndarray:
    """
    Buka gambar dari bytes, resize ke 299x299, dan terapkan
    preprocessing InceptionV3 (normalize ke [-1, 1]).
    """
    img       = Image.open(io.BytesIO(image_bytes)).convert('RGB')
    img       = img.resize((299, 299))
    img_array = tf.keras.preprocessing.image.img_to_array(img)
    img_array = np.expand_dims(img_array, axis=0)
    img_array = tf.keras.applications.inception_v3.preprocess_input(img_array)
    return img_array


# ─── ROUTE HEALTH CHECK ──────────────────────────────────────────────────────
@app.route('/health', methods=['GET'])
def health():
    """Endpoint sederhana untuk mengecek apakah server Python sudah aktif."""
    status = 'ok' if model_layer is not None else 'model_not_loaded'
    return jsonify({'status': status, 'model': MODEL_PATH}), 200


# ─── ROUTE PREDIKSI ──────────────────────────────────────────────────────────
@app.route('/predict', methods=['POST'])
def predict():
    print("\n" + "=" * 55)
    print("STATUS: Request masuk dari Laravel!")
    print("=" * 55)

    # Pastikan model sudah dimuat
    if model_layer is None:
        return jsonify({
            'success': False,
            'message': 'Model AI belum berhasil dimuat. Periksa log server.'
        }), 500

    # Cek apakah ada file gambar di request
    if 'image' not in request.files:
        print("STATUS: [ERROR] Key 'image' tidak ditemukan di request.files")
        return jsonify({'success': False, 'message': 'Tidak ada gambar yang diunggah (key: image)'}), 400

    file = request.files['image']

    if file.filename == '':
        print("STATUS: [ERROR] Nama file kosong")
        return jsonify({'success': False, 'message': 'Nama file kosong'}), 400

    try:
        print(f"STATUS: Gambar '{file.filename}' diterima. Mulai preprocessing...")

        image_bytes      = file.read()
        processed_image  = preprocess_image(image_bytes)

        print("STATUS: Gambar masuk ke Model AI, mohon tunggu...")

        # Inferensi
        outputs             = model_layer(processed_image)
        predictions_tensor  = list(outputs.values())[0]
        predictions         = predictions_tensor.numpy()[0]

        highest_index  = int(np.argmax(predictions))
        predicted_class = CLASS_NAMES[highest_index]
        confidence      = float(predictions[highest_index]) * 100

        # Semua skor per kelas (dalam persen, 2 desimal)
        all_scores = {
            CLASS_NAMES[i]: round(float(predictions[i]) * 100, 2)
            for i in range(len(CLASS_NAMES))
        }

        print(f"STATUS: [SUKSES] Hasil → {predicted_class} ({confidence:.2f}%)")
        print(f"STATUS: Semua skor → {all_scores}")

        return jsonify({
            'success'        : True,
            'predicted_class': predicted_class,       # ex: 'musang_king'
            'confidence'     : round(confidence, 2),  # ex: 98.4
            'all_scores'     : all_scores,            # ex: {'musang_king': 98.4, ...}
        }), 200

    except Exception as e:
        print(f"STATUS: [GAGAL] Error saat prediksi: {str(e)}")
        return jsonify({
            'success': False,
            'message': f'Terjadi kesalahan saat prediksi: {str(e)}'
        }), 500

for root, dirs, files in os.walk('model_durian_tf_temp'):
    print(root, files)

# ─── JALANKAN SERVER ─────────────────────────────────────────────────────────
if __name__ == '__main__':
    print("\nMenjalankan DurianFy Flask Server di http://0.0.0.0:5000")
    print("Tekan CTRL+C untuk menghentikan server.\n")
    app.run(host='0.0.0.0', port=5000, debug=False)