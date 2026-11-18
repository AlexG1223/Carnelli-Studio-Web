/**
 * Carnelli Studio — Main JS (ES Module)
 * Gestiona:
 * 1. Menú mobile accesible
 * 2. Generación de mensajes personalizados desde los formularios hacia WhatsApp (sin guardar en BD)
 * 3. Eventos de conversión (GA4 / Meta Pixel)
 * 4. Toggle interactivo de las Dos Capas en Inicio
 * 5. Wizard de 3 pasos en /contacto
 * 6. Lightbox accesible con teclado en Casos de Estudio
 */

document.documentElement.classList.add('js-enabled');

document.addEventListener('DOMContentLoaded', () => {
    initMobileNav();
    initConversionTracking();
    initLayersToggle();
    initContactWizard();
    initWhatsAppForms();
    initLightbox();
    initMagneticButtons();
});

function initMobileNav() {
    const toggle = document.getElementById('nav-toggle');
    const nav    = document.getElementById('site-nav');
    if (!toggle || !nav) return;

    toggle.addEventListener('click', () => {
        const isOpen = nav.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
}

function fireEvent(eventName, params = {}) {
    if (typeof window.gtag === 'function') {
        window.gtag('event', eventName, params);
    }
    if (typeof window.fbq === 'function') {
        window.fbq('trackCustom', eventName, params);
    }
}

function initConversionTracking() {
    document.querySelectorAll('[data-track="whatsapp"]').forEach(el => {
        el.addEventListener('click', () => {
            fireEvent('click_whatsapp', { page: window.location.pathname });
        });
    });

    document.querySelectorAll('[data-track="email"]').forEach(el => {
        el.addEventListener('click', () => {
            fireEvent('click_email', { page: window.location.pathname });
        });
    });

    document.querySelectorAll('[data-track="schedule"]').forEach(el => {
        el.addEventListener('click', () => {
            fireEvent('click_schedule', { page: window.location.pathname });
        });
    });
}

function initLayersToggle() {
    const buttons = document.querySelectorAll('[data-layer-tab]');
    const panels  = document.querySelectorAll('[data-layer-panel]');
    if (!buttons.length || !panels.length) return;

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-layer-tab');
            buttons.forEach(b => {
                const active = b === btn;
                b.classList.toggle('is-active', active);
                b.setAttribute('aria-selected', active ? 'true' : 'false');
            });

            panels.forEach(panel => {
                const panelLayer = panel.getAttribute('data-layer-panel');
                if (target === 'all' || target === panelLayer) {
                    panel.style.display = '';
                } else {
                    panel.style.display = 'none';
                }
            });
        });
    });
}

function initContactWizard() {
    const form = document.getElementById('contact-wizard-form');
    if (!form) return;

    const steps      = form.querySelectorAll('.wizard-step');
    const indicators = document.querySelectorAll('[data-step-ind]');
    const fillBar    = document.getElementById('wizard-progress-fill');

    function goToStep(stepNumber) {
        steps.forEach(st => {
            st.classList.toggle('is-active', Number(st.dataset.step) === stepNumber);
        });
        indicators.forEach(ind => {
            ind.classList.toggle('is-active', Number(ind.dataset.stepInd) <= stepNumber);
        });
        if (fillBar) {
            fillBar.style.width = `${(stepNumber / 3) * 100}%`;
        }
    }

    form.querySelectorAll('[data-wizard-next]').forEach(btn => {
        btn.addEventListener('click', () => {
            const nextStep = Number(btn.getAttribute('data-wizard-next'));
            if (nextStep === 3) {
                const negocioInput = form.querySelector('#wiz_negocio');
                if (negocioInput && !negocioInput.value.trim()) {
                    negocioInput.focus();
                    negocioInput.reportValidity();
                    return;
                }
            }
            goToStep(nextStep);
        });
    });

    form.querySelectorAll('[data-wizard-prev]').forEach(btn => {
        btn.addEventListener('click', () => {
            const prevStep = Number(btn.getAttribute('data-wizard-prev'));
            goToStep(prevStep);
        });
    });
}

function initWhatsAppForms() {
    document.querySelectorAll('form[data-wa-form]').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            const nombreEl  = form.querySelector('[name="nombre"]');
            const negocioEl = form.querySelector('[name="negocio"]');
            if (nombreEl && !nombreEl.value.trim()) {
                nombreEl.focus();
                nombreEl.reportValidity();
                return;
            }
            if (negocioEl && !negocioEl.value.trim()) {
                negocioEl.focus();
                negocioEl.reportValidity();
                return;
            }

            const phone     = form.getAttribute('data-wa-phone') || '59892473724';
            const nombre    = (nombreEl?.value || '').trim();
            const negocio   = (negocioEl?.value || '').trim();
            const rubro     = (form.querySelector('[name="rubro"]')?.value || '').trim();
            const situacion = (form.querySelector('[name="situacion_actual"]')?.value || '').trim();
            const plan      = (form.querySelector('[name="plan_interes"]')?.value || '').trim();
            const mensaje   = (form.querySelector('[name="mensaje"]')?.value || '').trim();

            const servicios = [];
            form.querySelectorAll('[name="servicios_interes[]"]').forEach(el => {
                if (el.tagName === 'SELECT') {
                    if (el.value) servicios.push(el.value);
                } else if (el.checked) {
                    servicios.push(el.value);
                }
            });

            const lines = ['¡Hola Alex! Te escribo desde la web de Carnelli Studio:'];
            if (nombre) lines.push(`• *Mi nombre:* ${nombre}`);
            if (negocio) lines.push(`• *Negocio:* ${negocio}${rubro ? ` (${rubro})` : ''}`);
            if (plan) lines.push(`• *Plan de interés:* ${plan}`);
            if (servicios.length) lines.push(`• *Qué necesito:* ${servicios.join(', ')}`);
            if (situacion) lines.push(`• *Situación actual:* ${situacion}`);
            if (mensaje) lines.push(`• *Detalle:* ${mensaje}`);

            const waUrl = `https://wa.me/${phone}?text=${encodeURIComponent(lines.join('\n'))}`;
            fireEvent('generate_lead_whatsapp', { page: window.location.pathname });
            window.open(waUrl, '_blank', 'noopener');
        });
    });
}

function initLightbox() {
    const modal    = document.getElementById('lightbox');
    const imgEl    = document.getElementById('lightbox-img');
    const capEl    = document.getElementById('lightbox-caption');
    const closeBtn = document.getElementById('lightbox-close');
    const prevBtn  = document.getElementById('lightbox-prev');
    const nextBtn  = document.getElementById('lightbox-next');
    const triggers = Array.from(document.querySelectorAll('[data-lightbox-src]'));

    if (!modal || !triggers.length) return;

    let currentIndex = 0;

    function showAt(index) {
        currentIndex = (index + triggers.length) % triggers.length;
        const item = triggers[currentIndex];
        imgEl.src = item.getAttribute('data-lightbox-src') || '';
        imgEl.alt = item.getAttribute('data-lightbox-alt') || '';
        capEl.textContent = item.getAttribute('data-lightbox-alt') || '';
        modal.hidden = false;
    }

    function close() {
        modal.hidden = true;
    }

    triggers.forEach((tr, idx) => {
        tr.addEventListener('click', () => showAt(idx));
    });

    closeBtn?.addEventListener('click', close);
    prevBtn?.addEventListener('click', () => showAt(currentIndex - 1));
    nextBtn?.addEventListener('click', () => showAt(currentIndex + 1));

    modal.addEventListener('click', (e) => {
        if (e.target === modal) close();
    });

    document.addEventListener('keydown', (e) => {
        if (modal.hidden) return;
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowLeft') showAt(currentIndex - 1);
        if (e.key === 'ArrowRight') showAt(currentIndex + 1);
    });
}

function initMagneticButtons() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    document.querySelectorAll('.btn--magnetic').forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = (e.clientX - rect.left - rect.width / 2) * 0.14;
            const y = (e.clientY - rect.top - rect.height / 2) * 0.14;
            btn.style.transform = `translate(${x}px, ${y}px)`;
        });
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = '';
        });
    });
}
