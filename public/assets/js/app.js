/* ============================================================
   MotoMarket — site scripts
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {
    initFavoriteButtons();
    initGallery();
    initConfirmModals();
    initAutoDismissAlerts();
    initImagePreviews();
    initTooltips();
});

/* ---------- Favorite toggle (AJAX) ---------- */
function initFavoriteButtons() {
    document.querySelectorAll('.fav-btn[data-url]').forEach((btn) => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();

            const isAuth = btn.dataset.auth === '1';
            if (!isAuth) {
                window.location.href = btn.dataset.loginUrl || '/login';
                return;
            }

            btn.disabled = true;

            try {
                const res = await fetch(btn.dataset.url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken(),
                        'Accept': 'application/json',
                    },
                });

                if (res.status === 401) {
                    window.location.href = btn.dataset.loginUrl || '/login';
                    return;
                }

                const data = await res.json();
                btn.classList.toggle('is-favorited', data.favorited);
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('bi-heart', !data.favorited);
                    icon.classList.toggle('bi-heart-fill', data.favorited);
                }
                showToast(data.message, data.favorited ? 'success' : 'info');
            } catch (err) {
                showToast('Something went wrong. Please try again.', 'danger');
            } finally {
                btn.disabled = false;
            }
        });
    });
}

/* ---------- Detail page gallery ---------- */
function initGallery() {
    const main = document.getElementById('galleryMain');
    if (!main) return;

    document.querySelectorAll('.gallery-thumb').forEach((thumb) => {
        thumb.addEventListener('click', () => {
            main.src = thumb.dataset.full;
            main.alt = thumb.alt;
            document.querySelectorAll('.gallery-thumb').forEach((t) => t.classList.remove('active'));
            thumb.classList.add('active');
        });
    });
}

/* ---------- Confirm before destructive actions ---------- */
function initConfirmModals() {
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            if (!window.confirm(form.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });
}

/* ---------- Auto-dismiss flash messages ---------- */
function initAutoDismissAlerts() {
    document.querySelectorAll('.alert-flash[data-autohide]').forEach((alertEl) => {
        setTimeout(() => {
            bootstrap.Alert.getOrCreateInstance(alertEl).close();
        }, 5000);
    });
}

/* ---------- Live preview for file inputs ---------- */
function initImagePreviews() {
    document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
        input.addEventListener('change', () => {
            const target = document.getElementById(input.dataset.preview);
            if (!target || !input.files || !input.files[0]) return;
            target.src = URL.createObjectURL(input.files[0]);
            target.classList.remove('d-none');
        });
    });
}

function initTooltips() {
    if (window.bootstrap?.Tooltip) {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => new bootstrap.Tooltip(el));
    }
}

/* ---------- Helpers ---------- */
function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

function showToast(message, type = 'success') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '2000';
        document.body.appendChild(container);
    }

    const el = document.createElement('div');
    el.className = `alert alert-${type} alert-flash shadow`;
    el.setAttribute('role', 'alert');
    el.textContent = message;
    container.appendChild(el);

    setTimeout(() => el.remove(), 3500);
}
