/**
 * Carnelli Studio — Admin JS
 * Contadores de caracteres en tiempo real para campos SEO (Meta Title y Meta Description).
 */

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-count-target]').forEach(input => {
        const key   = input.getAttribute('data-count-target');
        const badge = document.querySelector(`[data-char-count="${key}"]`);
        if (!badge) return;

        const update = () => {
            badge.textContent = String(input.value.length);
        };
        update();
        input.addEventListener('input', update);
    });
});

