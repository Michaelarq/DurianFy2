// filepath: app.js
import "./bootstrap";
import "../css/app.css";

document.addEventListener("DOMContentLoaded", () => {
    // mobile menu toggle (existing)
    const btn = document.querySelector("[data-toggle-menu]");
    const menu = document.querySelector("[data-menu]");
    if (btn && menu) {
        btn.addEventListener("click", () => menu.classList.toggle("hidden"));
    }

    // Upload preview & camera/gallery handling
    document.querySelectorAll('button[data-action]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const action = btn.dataset.action;
            const targetId = btn.dataset.target;
            const input = document.getElementById(targetId);
            if (!input) return;

            if (action === 'camera') {
                input.setAttribute('capture', 'environment');
            } else {
                input.removeAttribute('capture');
            }
            input.click();
        });
    });

    document.querySelectorAll('input[data-preview-target]').forEach(input => {
        input.addEventListener('change', (e) => {
            const file = input.files[0];
            const previewId = input.dataset.previewTarget;
            const img = document.getElementById(previewId);
            const empty = document.getElementById(previewId + '-empty');

            if (!file) {
                if (img) img.classList.add('hidden');
                if (empty) empty.classList.remove('hidden');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(ev) {
                if (img) {
                    img.src = ev.target.result;
                    img.classList.remove('hidden');
                }
                if (empty) empty.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });
    });

    // Reset all file inputs & previews
    const resetBtn = document.querySelector('[data-reset]');
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            document.querySelectorAll('input[type="file"][data-preview-target]').forEach(input => {
                input.value = null;
                const previewId = input.dataset.previewTarget;
                const img = document.getElementById(previewId);
                const empty = document.getElementById(previewId + '-empty');
                if (img) { img.src = ''; img.classList.add('hidden'); }
                if (empty) { empty.classList.remove('hidden'); }
            });
        });
    }
});