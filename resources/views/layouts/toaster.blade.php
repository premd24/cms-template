{{-- Premium Toast System Styles --}}
<style>
    #toast-container {
        position: fixed;
        top: 2rem;
        right: 2rem;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        pointer-events: none;
        max-width: calc(100% - 4rem);
        width: 24rem;
    }

    .premium-toast {
        pointer-events: auto;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        padding: 0.875rem 1.125rem;
        background: #ffffff;
        border: 1px solid rgba(228, 228, 231, 0.6); /* Very soft Zinc-200 border */
        border-radius: 0.75rem; /* Sleek modern 12px curves */
        box-shadow: 
            0 10px 15px -3px rgba(0, 0, 0, 0.03),
            0 4px 6px -4px rgba(0, 0, 0, 0.03),
            0 20px 25px -5px rgba(0, 0, 0, 0.06);
        transform: translateX(130%) scale(0.95);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); /* Elegant easeOutQuart */
        overflow: hidden;
        position: relative;
    }

    .premium-toast.show {
        transform: translateX(0) scale(1);
        opacity: 1;
    }

    .premium-toast.hide {
        transform: translateX(130%) scale(0.95);
        opacity: 0;
        margin-top: -4rem;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .toast-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .toast-success .toast-icon-wrapper {
        background: rgba(87, 222, 107, 0.1);
        color: #15803d; /* Deep professional green */
        border: 1px solid rgba(87, 222, 107, 0.15);
    }

    .toast-error .toast-icon-wrapper {
        background: rgba(239, 68, 68, 0.08);
        color: #b91c1c; /* Deep professional red */
        border: 1px solid rgba(239, 68, 68, 0.12);
    }

    .toast-content {
        flex-grow: 1;
    }

    .toast-title {
        font-weight: 700;
        color: #18181b; /* Zinc 900 */
        font-size: 0.875rem;
        line-height: 1.25;
        letter-spacing: -0.01em;
    }

    .toast-msg {
        font-size: 0.8125rem;
        color: #71717a; /* Zinc 600 */
        margin-top: 0.125rem;
        line-height: 1.4;
        font-weight: 500;
    }

    .toast-close-btn {
        color: #a1a1aa; /* Zinc 400 */
        transition: all 0.2s ease-in-out;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast-close-btn:hover {
        color: #52525b; /* Zinc 600 */
        background: #f4f4f5; /* Zinc 100 */
    }

    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 2px;
        background: #57DE6B; /* Sleek theme indicator line */
        width: 100%;
        transform-origin: left;
    }

    .toast-error .toast-progress {
        background: #ef4444;
    }
</style>

{{-- Premium Toast System Script --}}
<script>
    window.premiumToast = {
        show: function(title, message, type = 'success', duration = 4000) {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            toast.className = `premium-toast toast-${type}`;
            
            const successIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>`;
            const errorIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>`;
            const closeIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>`;

            const icon = type === 'success' ? successIcon : errorIcon;

            toast.innerHTML = `
                <div class="toast-icon-wrapper">
                    ${icon}
                </div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    ${message ? `<div class="toast-msg">${message}</div>` : ''}
                </div>
                <button class="toast-close-btn" aria-label="Close message">
                    ${closeIcon}
                </button>
                <div class="toast-progress"></div>
            `;

            container.appendChild(toast);

            setTimeout(() => toast.classList.add('show'), 50);

            const progress = toast.querySelector('.toast-progress');
            progress.style.transition = `transform ${duration}ms linear`;
            progress.style.transform = 'scaleX(1)';
            setTimeout(() => {
                progress.style.transform = 'scaleX(0)';
            }, 50);

            const timer = setTimeout(() => {
                closeToast(toast);
            }, duration);

            toast.querySelector('.toast-close-btn').addEventListener('click', () => {
                clearTimeout(timer);
                closeToast(toast);
            });

            function closeToast(el) {
                el.classList.remove('show');
                el.classList.add('hide');
                setTimeout(() => {
                    el.remove();
                }, 400);
            }
        },
        success: function(message, title = 'Success') {
            this.show(title, message, 'success');
        },
        error: function(message, title = 'Error') {
            this.show(title, message, 'error');
        }
    };

    // Also define a simple helper alias
    window.toast = window.premiumToast;
</script>

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.toast) {
                window.toast.success("{{ session('success') }}");
            }
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.toast) {
                window.toast.error("{{ session('error') }}");
            }
        });
    </script>
@endif
