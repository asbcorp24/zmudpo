(function () {
  'use strict';

  const rootSelector = '[data-ajax-root]';
  const progress = () => document.getElementById('ajax-progress');
  let activeRequest = null;

  const sameOrigin = (url) => {
    try { return new URL(url, window.location.href).origin === window.location.origin; }
    catch (_) { return false; }
  };

  const skipUrl = (url) => {
    const path = new URL(url, window.location.href).pathname;
    return /\/download(?:\/|$)|\/package(?:\/|$)|\/legacy-file(?:\/|$)|\/print(?:\/|$)/i.test(path);
  };

  const shouldHandleLink = (a) => {
    if (!a || a.dataset.noAjax !== undefined || a.hasAttribute('download')) return false;
    if (a.target && a.target !== '_self') return false;
    const href = a.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return false;
    if (!sameOrigin(href) || skipUrl(href)) return false;
    return true;
  };

  const shouldHandleForm = (form) => {
    if (!form || form.dataset.noAjax !== undefined) return false;
    const target = form.getAttribute('target');
    if (target && target !== '_self') return false;
    const action = form.getAttribute('action') || window.location.href;
    return sameOrigin(action) && !skipUrl(action);
  };

  const setBusy = (busy) => {
    document.body.classList.toggle('ajax-busy', busy);
    if (progress()) progress().classList.toggle('active', busy);
  };

  const showToast = (message, type) => {
    if (!message) return;
    let host = document.getElementById('ajax-toast-host');
    if (!host) {
      host = document.createElement('div');
      host.id = 'ajax-toast-host';
      host.className = 'toast-container position-fixed top-0 end-0 p-3';
      host.style.zIndex = '2000';
      document.body.appendChild(host);
    }
    const el = document.createElement('div');
    el.className = 'toast align-items-center border-0 text-bg-' + (type || 'primary');
    el.setAttribute('role', 'status');
    el.innerHTML = '<div class="d-flex"><div class="toast-body"></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
    el.querySelector('.toast-body').textContent = message;
    host.appendChild(el);
    if (window.bootstrap && bootstrap.Toast) {
      const toast = new bootstrap.Toast(el, { delay: 3500 });
      el.addEventListener('hidden.bs.toast', () => el.remove());
      toast.show();
    } else {
      setTimeout(() => el.remove(), 3500);
    }
  };

  const runScripts = (container) => {
    container.querySelectorAll('script').forEach((oldScript) => {
      const s = document.createElement('script');
      Array.from(oldScript.attributes).forEach((a) => s.setAttribute(a.name, a.value));
      s.textContent = oldScript.textContent;
      oldScript.replaceWith(s);
    });
  };

  const updateActiveNavigation = () => {
    const current = window.location.pathname.replace(/\/$/, '') || '/';
    document.querySelectorAll('.app-nav a[href]').forEach((a) => {
      let path;
      try { path = new URL(a.href).pathname.replace(/\/$/, '') || '/'; }
      catch (_) { return; }
      const active = path === current || (path !== '/' && current.startsWith(path + '/'));
      a.classList.toggle('active', active);
    });
  };

  const replacePage = (html, url, push) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const incoming = doc.querySelector(rootSelector);
    const current = document.querySelector(rootSelector);
    if (!incoming || !current) {
      window.location.href = url;
      return;
    }
    current.innerHTML = incoming.innerHTML;
    const title = doc.querySelector('title');
    if (title) document.title = title.textContent;
    if (push) history.pushState({ ajax: true }, '', url);
    runScripts(current);
    updateActiveNavigation();
    window.scrollTo({ top: 0, behavior: 'instant' });
    document.body.classList.remove('sidebar-open');
    document.dispatchEvent(new CustomEvent('zmudpo:page-loaded', { detail: { url } }));
  };

  const requestHtml = async (url, options, push) => {
    if (activeRequest) activeRequest.abort();
    activeRequest = new AbortController();
    setBusy(true);
    try {
      const response = await fetch(url, Object.assign({
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-Ajax-Navigation': '1' },
        signal: activeRequest.signal
      }, options || {}));

      if (response.status === 401 || response.status === 419) {
        window.location.reload();
        return;
      }

      const type = response.headers.get('content-type') || '';
      if (!type.includes('text/html')) {
        window.location.href = response.url || url;
        return;
      }

      const html = await response.text();
      replacePage(html, response.url || url, push);

      if (!response.ok) {
        showToast('Проверьте введённые данные.', 'danger');
      }
    } catch (e) {
      if (e.name !== 'AbortError') {
        console.error(e);
        showToast('Не удалось выполнить запрос. Проверьте соединение.', 'danger');
      }
    } finally {
      activeRequest = null;
      setBusy(false);
    }
  };

  document.addEventListener('click', (e) => {
    const menu = e.target.closest('[data-sidebar-toggle]');
    if (menu) {
      e.preventDefault();
      document.body.classList.toggle('sidebar-open');
      return;
    }

    const a = e.target.closest('a');
    if (!shouldHandleLink(a)) return;
    if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey || e.button !== 0) return;
    e.preventDefault();
    requestHtml(a.href, { method: 'GET' }, true);
  });

  document.addEventListener('submit', (e) => {
    const form = e.target;
    if (!shouldHandleForm(form)) return;
    e.preventDefault();

    const method = (form.getAttribute('method') || 'GET').toUpperCase();
    const action = form.getAttribute('action') || window.location.href;
    const data = new FormData(form);

    if (method === 'GET') {
      const url = new URL(action, window.location.href);
      data.forEach((value, key) => {
        if (value !== '') url.searchParams.set(key, value);
      });
      requestHtml(url.toString(), { method: 'GET' }, true);
      return;
    }

    const submitter = e.submitter;
    if (submitter && submitter.name) data.append(submitter.name, submitter.value);

    requestHtml(action, { method: 'POST', body: data }, true);
  });

  window.addEventListener('popstate', () => requestHtml(window.location.href, { method: 'GET' }, false));

  document.addEventListener('DOMContentLoaded', updateActiveNavigation);
  updateActiveNavigation();
})();