/**
 * Carnelli Studio — Main JS (ES Module)
 * Gestiona:
 * 1. Menú mobile accesible
 * 2. Captura de parámetros UTM al entrar al sitio y relleno automático en formularios
 * 3. Eventos de conversión (GA4 / Meta Pixel) en WhatsApp, Email, Agenda y envío de formularios
 * 4. Toggle interactivo de las Dos Capas en Inicio
 * 5. Filtro animado de proyectos sin recargar (Fetch JSON + History API)
 * 6. Wizard inteligente de contacto en 3 pasos con guardado de borrador en sessionStorage
 * 7. Lightbox accesible con teclado y swipe táctil en Casos de Estudio
 */

document.documentElement.classList.add('js-enabled');

document.addEventListener('DOMContentLoaded', () => {
    initMobileNav();
    initUtmCapture();
    initConversionTracking();
    initLayersToggle();
    initPortfolioFilter();
    initContactWizard();
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

function initUtmCapture() {
    const params = new URLSearchParams(window.location.search);
    ['utm_source', 'utm_medium', 'utm_campaign'].forEach(key => {
        const val = params.get(key);
        if (val) {
            sessionStorage.setItem('cs_' + key, val);
        }
        const stored = sessionStorage.getItem('cs_' + key) || '';
        document.querySelectorAll(`input[name="${key}"]`).forEach(input => {
            input.value = stored;
        });
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

    document.querySelectorAll('form[data-form="lead"]').forEach(form => {
        form.addEventListener('submit', () => {
            fireEvent('generate_lead', { page: window.location.pathname });
            sessionStorage.removeItem('cs_wizard_draft');
        });
    });

    if (document.querySelector('[data-conversion="lead_submitted"]')) {
        fireEvent('lead_thank_you_page', { page: '/gracias' });
    }
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

function initPortfolioFilter() {
    const grid  = document.getElementById('portfolio-grid');
    const pills = document.querySelectorAll('[data-filter-slug]');
    if (!grid || !pills.length) return;

    pills.forEach(pill => {
        pill.addEventListener('click', async (e) => {
            e.preventDefault();
            const slug = pill.getAttribute('data-filter-slug') || 'todos';
            const href = pill.getAttribute('href') || window.location.pathname;

            pills.forEach(p => p.classList.toggle('is-active', p === pill));

            try {
                const baseUrl = href.split('?')[0].replace(/\/proyectos$/, '');
                const apiUrl  = `${baseUrl}/api/proyectos?servicio=${encodeURIComponent(slug)}`;
                const res     = await fetch(apiUrl, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('Error de red');
                const data = await res.json();
                if (data.ok) {
                    grid.innerHTML = data.html || '<div class="empty-state"><h3>No hay proyectos para ese filtro puntual</h3></div>';
                    window.history.pushState({}, '', href);
                }
            } catch {
                window.location.href = href;
            }
        });
    });
}

function initContactWizard() {
    const form = document.getElementById('contact-wizard-form');
    if (!form) return;

    const steps      = form.querySelectorAll('.wizard-step');
    const indicators = document.querySelectorAll('[data-step-ind]');
    const fillBar    = document.getElementById('wizard-progress-fill');
    const storageKey = 'cs_wizard_draft';

    // Restaurar borrador desde sessionStorage
    try {
        const saved = JSON.parse(sessionStorage.getItem(storageKey) || '{}');
        Object.entries(saved).forEach(([name, val]) => {
            const field = form.querySelector(`[name="${name}"]`);
            if (field && field.type !== 'checkbox' && field.type !== 'radio' && !field.value) {
                field.value = val;
            }
        });
    } catch {}

    // Guardar cambios en sessionStorage
    form.addEventListener('input', () => {
        const draft = {};
        ['negocio', 'rubro', 'situacion_actual', 'mensaje', 'nombre', 'whatsapp', 'email'].forEach(k => {
            const el = form.querySelector(`[name="${k}"]`);
            if (el) draft[k] = el.value;
        });
        sessionStorage.setItem(storageKey, JSON.stringify(draft));
    });

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

function initLightbox() {
    const modal   = document.getElementById('lightbox');
    const imgEl   = document.getElementById('lightbox-img');
    const capEl   = document.getElementById('lightbox-caption');
    const closeBtn= document.getElementById('lightbox-close');
    const prevBtn = document.getElementById('lightbox-prev');
    const nextBtn = document.getElementById('lightbox-next');
    const triggers= Array.from(document.querySelectorAll('[data-lightbox-src]'));

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

