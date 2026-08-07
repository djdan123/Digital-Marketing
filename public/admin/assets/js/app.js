/**
 * Point d'entrée global de l'admin TruckAll
 */
import { requireAuth } from './auth.js';
import { initSidebarToggle, renderSidebarNav, updateNavbarUser } from './sidebar.js';

const AUTH_PAGES = ['login.html', 'register.html', 'forgot-password.html'];

const isAuthPage = () => AUTH_PAGES.some((p) => window.location.pathname.endsWith(p));

const loadComponent = async (selector, url) => {
  const el = document.querySelector(selector);
  if (!el) return;
  try {
    const res = await fetch(url);
    if (!res.ok) return;
    el.innerHTML = await res.text();
  } catch {
    /* composant optionnel */
  }
};

const loadPageModule = async () => {
  const page = document.body.dataset.page;
  if (!page) return;

  const modules = {
    dashboard: () => import('./modules/dashboard.js'),
    campaign: () => import('./modules/campaign.js'),
    advertisement: () => import('./modules/advertisement.js'),
    media: () => import('./modules/media.js'),
    schedule: () => import('./modules/schedule.js'),
    payment: () => import('./modules/payment.js'),
    user: () => import('./modules/user.js'),
    statistics: () => import('./modules/statistics.js'),
  };

  const loader = modules[page];
  if (loader) {
    const mod = await loader();
    mod.init?.();
  }
};

const hideLoading = () => {
  document.getElementById('app-loading')?.classList.add('hidden');
};

export const initApp = async () => {
  if (!isAuthPage() && !requireAuth()) return;

  await loadComponent('#sidebar-container', '/admin/components/sidebar.html');
  await loadComponent('#navbar-container', '/admin/components/navbar.html');
  await loadComponent('#footer-container', '/admin/components/footer.html');

  renderSidebarNav(document.getElementById('sidebar-nav'));
  updateNavbarUser();
  initSidebarToggle();
  await loadPageModule();
  hideLoading();
};

// Ancien point d'entrée — ne s'exécute que sur les pages legacy explicites
document.addEventListener('DOMContentLoaded', () => {
  if (document.body.dataset.legacyApp === 'true') {
    initApp();
  }
});
