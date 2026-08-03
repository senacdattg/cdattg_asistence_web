/**
 * SPA real AITG — navegación por fragmentos AJAX + caché + prefetch.
 * No usa wire:navigate (evita recargar AdminLTE completo ~3s).
 */

const AITG_PREFIX = '/aitg/';
const SPA_HEADER = 'X-AITG-SPA';
const DOWNLOAD_RE = /(\/pdf|\/excel|\/download|\/descargar|\/ver-archivo|\/archivos\/|\/stream)/i;
const BINARY_EXT_RE = /\.(pdf|xlsx|xls|csv|zip|docx?)($|\?)/i;
const CACHE_TTL_MS = 90_000;
const MAX_CACHE = 24;

/** @type {Map<string, { html: string, title: string, at: number }>} */
const pageCache = new Map();
/** @type {Map<string, Promise<{ html: string, title: string }>>} */
const inflight = new Map();

let navigating = false;
let lastUrl = normalizeUrl(globalThis.location.href);

function normalizeUrl(href) {
    const url = new URL(href, globalThis.location.origin);
    url.hash = '';
    // Quitar flag de fragmento si viniera en query
    url.searchParams.delete('aitg_spa');
    return url.pathname + url.search;
}

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function isAitgInternalLink(anchor) {
    if (anchor?.tagName !== 'A') {
        return false;
    }

    if (anchor.hasAttribute('download') || anchor.target === '_blank') {
        return false;
    }

    if (anchor.dataset.aitgSpaIgnore === '1' || anchor.dataset.spaIgnore === '1') {
        return false;
    }

    const href = anchor.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:')) {
        return false;
    }

    let url;
    try {
        url = new URL(anchor.href, globalThis.location.origin);
    } catch {
        return false;
    }

    if (url.origin !== globalThis.location.origin) {
        return false;
    }

    if (!url.pathname.startsWith(AITG_PREFIX) && url.pathname !== '/aitg') {
        return false;
    }

    if (DOWNLOAD_RE.test(url.pathname) || BINARY_EXT_RE.test(url.pathname + url.search)) {
        return false;
    }

    if (anchor.closest('form[enctype*="multipart"]')) {
        return false;
    }

    // POST/PUT links no
    if ((anchor.dataset.method || '').toLowerCase() === 'post') {
        return false;
    }

    return true;
}

function setNavigating(active) {
    navigating = active;
    const root = document.querySelector('[data-aitg-spa-root]');
    const progress = document.querySelector('[data-aitg-spa-progress]');
    const shell = document.querySelector('[data-aitg-spa-shell]');

    root?.classList.toggle('is-navigating', active);
    shell?.classList.toggle('is-navigating', active);

    if (progress) {
        progress.hidden = !active;
    }
}

function rememberCache(key, payload) {
    if (pageCache.has(key)) {
        pageCache.delete(key);
    }
    pageCache.set(key, { ...payload, at: Date.now() });

    while (pageCache.size > MAX_CACHE) {
        const oldest = pageCache.keys().next().value;
        pageCache.delete(oldest);
    }
}

function readCache(key) {
    const hit = pageCache.get(key);
    if (!hit) {
        return null;
    }
    if (Date.now() - hit.at > CACHE_TTL_MS) {
        pageCache.delete(key);
        return null;
    }
    return hit;
}

async function fetchFragment(href, { prefetch = false } = {}) {
    const key = normalizeUrl(href);
    const cached = readCache(key);
    if (cached) {
        return cached;
    }

    if (inflight.has(key)) {
        return inflight.get(key);
    }

    const request = (async () => {
        const response = await fetch(key, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                Accept: 'text/html',
                'X-Requested-With': 'XMLHttpRequest',
                [SPA_HEADER]: '1',
                'X-CSRF-TOKEN': csrfToken(),
            },
        });

        if (response.redirected) {
            // Login u otra redirección: salir del SPA
            throw new Error('redirect:' + response.url);
        }

        if (!response.ok) {
            throw new Error('http:' + response.status);
        }

        const html = await response.text();
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const fragment = doc.querySelector('[data-aitg-spa-fragment], [data-aitg-spa-root]');

        if (!fragment) {
            throw new Error('fragment-missing');
        }

        const title = fragment.dataset.aitgTitle
            || doc.querySelector('title')?.textContent
            || 'AITG';

        const payload = { html: fragment.outerHTML, title: title.trim() };
        rememberCache(key, payload);
        return payload;
    })();

    inflight.set(key, request);

    try {
        return await request;
    } finally {
        inflight.delete(key);
        if (prefetch) {
            // no-op: ya quedó en cache
        }
    }
}

function runInlineScripts(container) {
    const scriptHost = container.querySelector('[data-aitg-spa-scripts]');
    const nodes = [
        ...(scriptHost ? [...scriptHost.querySelectorAll('script')] : []),
        ...[...container.querySelectorAll('script')].filter((s) => !scriptHost?.contains(s)),
    ];

    nodes.forEach((oldScript) => {
        if (oldScript.dataset.aitgSpaRan === '1') {
            return;
        }
        oldScript.dataset.aitgSpaRan = '1';

        const script = document.createElement('script');
        [...oldScript.attributes].forEach((attr) => {
            script.setAttribute(attr.name, attr.value);
        });

        if (oldScript.src) {
            script.src = oldScript.src;
            // Evitar re-ejecutar vite bundles ya cargados
            if ([...document.scripts].some((s) => s.src === script.src)) {
                oldScript.remove();
                return;
            }
            script.async = false;
            document.body.appendChild(script);
        } else {
            script.textContent = oldScript.textContent || '';
            document.body.appendChild(script);
            script.remove();
        }
        oldScript.remove();
    });

    scriptHost?.remove();
}

function markActiveNav(pathname = globalThis.location.pathname) {
    const path = pathname.replace(/\/+$/, '') || '/';
    const links = [...document.querySelectorAll('[data-aitg-spa-nav] [data-aitg-spa-link]')];
    let best = null;
    let bestLen = -1;

    links.forEach((link) => {
        link.classList.remove('is-active');
        try {
            const linkPath = new URL(link.href, globalThis.location.origin).pathname.replace(/\/+$/, '');
            const key = link.dataset.aitgKey;
            let matches = path === linkPath || path.startsWith(`${linkPath}/`);

            if (key === 'convocatorias-gestion' && path.startsWith('/aitg/convocatorias/publicas')) {
                matches = false;
            }

            if (matches && linkPath.length > bestLen) {
                best = link;
                bestLen = linkPath.length;
            }
        } catch {
            // ignore
        }
    });

    best?.classList.add('is-active');
}

function initValidationToggles(root = document) {
    root.querySelectorAll('.aitg-toggle-rechazo').forEach((select) => {
        if (select.dataset.aitgSpaBound === '1') {
            return;
        }
        select.dataset.aitgSpaBound = '1';

        const toggle = () => {
            const wrap = select.closest('.aitg-validacion-fila, .aitg-validacion-form');
            const motivo = wrap?.querySelector('.aitg-campo-motivo');
            const motivoSelect = wrap?.querySelector('.aitg-motivo-select');
            if (motivo) {
                motivo.style.display = select.value === 'rechazado' ? 'block' : 'none';
            }
            if (motivoSelect) {
                motivoSelect.required = select.value === 'rechazado';
            }
        };

        select.addEventListener('change', toggle);
        toggle();
    });
}

function initPlanesPorCompetencia(root = document) {
    const competencia = root.querySelector('#competencia_id') || document.getElementById('competencia_id');
    const planSelect = root.querySelector('#plan_contratacion_id') || document.getElementById('plan_contratacion_id');
    if (!competencia || !planSelect || competencia.dataset.aitgSpaBound === '1') {
        return;
    }

    competencia.dataset.aitgSpaBound = '1';
    const endpoint = competencia.dataset.planesUrl || null;

    competencia.addEventListener('change', () => {
        if (!competencia.value || !endpoint) {
            return;
        }

        fetch(`${endpoint}?competencia_id=${encodeURIComponent(competencia.value)}`, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        })
            .then((r) => r.json())
            .then((items) => {
                planSelect.innerHTML = '<option value="">Seleccione plan...</option>';
                items.forEach((item) => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.label;
                    planSelect.appendChild(option);
                });
            })
            .catch(() => {});
    });
}

function bindPrefetch(root = document) {
    root.querySelectorAll('a[data-aitg-spa-prefetch], [data-aitg-spa-nav] a[href], .main-sidebar a[href*="/aitg/"]').forEach((anchor) => {
        if (anchor.dataset.aitgPrefetchBound === '1' || !isAitgInternalLink(anchor)) {
            return;
        }
        anchor.dataset.aitgPrefetchBound = '1';

        const prefetch = () => {
            fetchFragment(anchor.href, { prefetch: true }).catch(() => {});
        };

        anchor.addEventListener('mouseenter', prefetch, { passive: true });
        anchor.addEventListener('touchstart', prefetch, { passive: true });
        anchor.addEventListener('focus', prefetch, { passive: true });
    });
}

function bootUi(root = document) {
    markActiveNav();
    initValidationToggles(root);
    initPlanesPorCompetencia(root);
    bindPrefetch(root);
    document.dispatchEvent(new CustomEvent('aitg:spa-ready', { detail: { root } }));
}

function applyFragment(payload, href, { push = true } = {}) {
    const currentRoot = document.querySelector('[data-aitg-spa-root]');
    if (!currentRoot) {
        globalThis.location.href = href;
        return;
    }

    const template = document.createElement('template');
    template.innerHTML = payload.html.trim();
    const nextRoot = template.content.querySelector('[data-aitg-spa-root]');

    if (!nextRoot) {
        globalThis.location.href = href;
        return;
    }

    currentRoot.replaceWith(nextRoot);
    document.title = payload.title || document.title;

    const url = normalizeUrl(href);
    if (push && url !== normalizeUrl(globalThis.location.href)) {
        history.pushState({ aitgSpa: true, url }, payload.title, url);
    }

    lastUrl = url;
    runInlineScripts(nextRoot);
    bootUi(nextRoot);

    const top = Math.max(0, nextRoot.getBoundingClientRect().top + globalThis.scrollY - 12);
    globalThis.scrollTo(0, top);
}

async function navigate(href, { push = true } = {}) {
    const key = normalizeUrl(href);
    if (navigating && key === lastUrl) {
        return;
    }

    // Si aún no estamos dentro del shell AITG, carga completa una vez
    if (!document.querySelector('[data-aitg-spa-root]')) {
        globalThis.location.href = href;
        return;
    }

    setNavigating(true);

    try {
        const payload = await fetchFragment(href);
        applyFragment(payload, href, { push });
    } catch (error) {
        const message = String(error?.message || error);
        if (message.startsWith('redirect:')) {
            globalThis.location.href = message.slice('redirect:'.length);
            return;
        }
        // Fallback seguro
        globalThis.location.href = href;
        return;
    } finally {
        setNavigating(false);
    }
}

function onClick(event) {
    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
        return;
    }

    const anchor = event.target.closest?.('a[href]');
    if (!anchor || !isAitgInternalLink(anchor)) {
        return;
    }

    // Interceptar también sidebar AdminLTE (wire:navigate) para AITG
    event.preventDefault();
    event.stopImmediatePropagation();

    const url = normalizeUrl(anchor.href);
    if (url === normalizeUrl(globalThis.location.href) && document.querySelector('[data-aitg-spa-root]')) {
        // Misma URL: refrescar desde red
        pageCache.delete(url);
    }

    navigate(anchor.href, { push: true });
}

function onPopState() {
    const url = normalizeUrl(globalThis.location.href);
    if (!url.startsWith(AITG_PREFIX) && url !== '/aitg') {
        globalThis.location.reload();
        return;
    }
    navigate(globalThis.location.href, { push: false });
}

function warmSubnav() {
    document.querySelectorAll('[data-aitg-spa-nav] a[href]').forEach((anchor, index) => {
        if (!isAitgInternalLink(anchor)) {
            return;
        }
        // Prefetch escalonado de submódulos visibles
        globalThis.setTimeout(() => {
            fetchFragment(anchor.href, { prefetch: true }).catch(() => {});
        }, 120 + index * 90);
    });
}

function boot() {
    // Quitar wire:navigate de enlaces AITG para que no compita con este SPA
    document.querySelectorAll(String.raw`a[href*="/aitg/"][wire\:navigate], a[href*="/aitg/"][wire\:navigate\.hover]`).forEach((a) => {
        a.removeAttribute('wire:navigate');
        a.removeAttribute('wire:navigate.hover');
    });

    // Cachear la página actual si ya estamos en AITG
    const root = document.querySelector('[data-aitg-spa-root]');
    if (root) {
        rememberCache(normalizeUrl(globalThis.location.href), {
            html: root.outerHTML,
            title: document.title,
        });
    }

    bootUi(document);
    warmSubnav();
}

document.addEventListener('DOMContentLoaded', boot);
document.addEventListener('livewire:navigated', () => {
    // Si Livewire nos trae a AITG desde otro módulo, re-bootear SPA
    boot();
});

document.addEventListener('click', onClick, true);
globalThis.addEventListener('popstate', onPopState);
