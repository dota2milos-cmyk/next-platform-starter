// Toast notifications
function showToast(msg, type = 'success') {
    const id = 'toast_' + Date.now();
    const html = `<div id="${id}" class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 show" role="alert">
        <div class="d-flex"><div class="toast-body fw-semibold">${msg}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="document.getElementById('${id}').remove()"></button></div></div>`;
    let container = document.querySelector('.toast-container');
    if (!container) { container = document.createElement('div'); container.className = 'toast-container'; document.body.appendChild(container); }
    container.insertAdjacentHTML('beforeend', html);
    setTimeout(() => { const el = document.getElementById(id); if (el) el.remove(); }, 3500);
}

// Design upload
function initDesignUpload() {
    const area = document.getElementById('designUploadArea');
    const input = document.getElementById('designFile');
    const preview = document.getElementById('designPreview');
    if (!area || !input) return;

    area.addEventListener('click', () => input.click());
    area.addEventListener('dragover', e => { e.preventDefault(); area.classList.add('dragover'); });
    area.addEventListener('dragleave', () => area.classList.remove('dragover'));
    area.addEventListener('drop', e => {
        e.preventDefault();
        area.classList.remove('dragover');
        if (e.dataTransfer.files[0]) handleFile(e.dataTransfer.files[0]);
    });
    input.addEventListener('change', () => { if (input.files[0]) handleFile(input.files[0]); });

    function handleFile(file) {
        if (!file.type.startsWith('image/')) { showToast('Molimo uploadajte sliku.', 'error'); return; }
        if (file.size > 10 * 1024 * 1024) { showToast('Maksimalna veličina je 10MB.', 'error'); return; }
        const reader = new FileReader();
        reader.onload = e => {
            if (preview) { preview.src = e.target.result; preview.style.display = 'block'; }
            area.querySelector('p').textContent = '✅ ' + file.name;
        };
        reader.readAsDataURL(file);
    }
}

// Size/color selector
function initSelectors() {
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const input = document.getElementById('selectedSize');
            if (input) input.value = btn.dataset.size;
        });
    });
    document.querySelectorAll('.color-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const input = document.getElementById('selectedColor');
            if (input) input.value = btn.dataset.color;
            const label = document.getElementById('colorLabel');
            if (label) label.textContent = btn.dataset.color;
        });
    });
}

// Quantity updater in cart
function updateQty(key, delta) {
    const input = document.getElementById('qty_' + key);
    if (!input) return;
    const newVal = Math.max(1, parseInt(input.value) + delta);
    input.value = newVal;
    fetch('/cart/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `key=${encodeURIComponent(key)}&qty=${newVal}&_token=${csrfToken()}`
    }).then(() => location.reload());
}

function removeItem(key) {
    fetch('/cart/remove', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `key=${encodeURIComponent(key)}&_token=${csrfToken()}`
    }).then(() => location.reload());
}

function csrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : '';
}

// Add to cart AJAX
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.classList.contains('add-to-cart-form')) {
        e.preventDefault();
        const fd = new FormData(form);
        fetch('/cart/add', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('✅ Dodano u košaricu!');
                    const badge = document.querySelector('.cart-count');
                    if (badge && data.count) badge.textContent = data.count;
                } else {
                    showToast(data.error || 'Greška.', 'error');
                }
            });
    }
});

// Star rating
function initStarRating() {
    const stars = document.querySelectorAll('.star-rate');
    stars.forEach(star => {
        star.addEventListener('click', () => {
            const val = star.dataset.value;
            document.getElementById('ratingValue').value = val;
            stars.forEach(s => s.classList.toggle('text-warning', parseInt(s.dataset.value) <= parseInt(val)));
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initDesignUpload();
    initSelectors();
    initStarRating();

    // Flash messages
    const flash = document.getElementById('flashMessage');
    if (flash) {
        showToast(flash.dataset.message, flash.dataset.type || 'success');
    }
});
