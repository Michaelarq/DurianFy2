import os
import io
import numpy as np
import tensorflow as tf
from flask import Flask, request, jsonify
from flask_cors import CORS
from PIL import Image

GDRIVE_FOLDER_ID = os.environ.get('GDRIVE_FOLDER_ID', '')

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
        print("[OK] Model sudah ada, skip download.")
        return True

    if not GDRIVE_FOLDER_ID:
        print("[GAGAL] GDRIVE_FOLDER_ID tidak diset!")
        return False

    print("Mendownload model dari Google Drive...")
    try:
        url = f'https://drive.google.com/drive/folders/{GDRIVE_FOLDER_ID}'
        gdown.download_folder(url, output='.', quiet=False, use_cookies=False)
        print("[OK] Download selesai!")
        return True
    except Exception as e:
        print(f"[GAGAL] Download folder gagal: {e}")
        # Coba cara alternatif — download file satu per satu
        return False

download_model()
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


# ─── JALANKAN SERVER ─────────────────────────────────────────────────────────
if __name__ == '__main__':
    print("\nMenjalankan DurianFy Flask Server di http://0.0.0.0:5000")
    print("Tekan CTRL+C untuk menghentikan server.\n")
    app.run(host='0.0.0.0', port=5000, debug=False)