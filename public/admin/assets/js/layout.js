/**
 * Utilitaires partagés pour toutes les pages admin TruckAll
 * Design : Flowbite Dark Mode
 */
import { requireAuth } from './auth.js';

/** Charge la sidebar Flowbite depuis components/sidebar.html */
export function loadSidebar() {
  return fetch('/admin/components/sidebar.html')
    .then((res) => res.text())
    .then((html) => {
      const container = document.getElementById('sidebar-container');
      if (container) container.innerHTML = html;
    })
    .catch(() => {});
}

/** Initialise une page admin protégée (auth + sidebar) */
export function initAdminPage() {
  requireAuth();
  return loadSidebar();
}

/** Affiche une alerte en haut de page */
export function showAlert(elementId, message, type = 'error') {
  const el = document.getElementById(elementId);
  if (!el) return;
  el.classList.remove('hidden');
  el.className = `mb-6 p-4 rounded-lg text-sm border ${
    type === 'success'
      ? 'bg-green-800 text-green-100 border-green-700'
      : 'bg-red-800 text-red-100 border-red-700'
  }`;
  el.textContent = message;
}

/** Badge de statut campagne / publicité */
export function statusBadge(status, labels = {}) {
  const styles = {
    draft: 'bg-gray-600 text-gray-100',
    pending: 'bg-yellow-600 text-yellow-100',
    approved: 'bg-blue-600 text-blue-100',
    active: 'bg-green-600 text-green-100',
    completed: 'bg-purple-600 text-purple-100',
    cancelled: 'bg-red-600 text-red-100',
    rejected: 'bg-red-600 text-red-100',
    scheduled: 'bg-blue-600 text-blue-100',
    failed: 'bg-red-600 text-red-100',
  };
  const defaultLabels = {
    draft: 'Brouillon',
    pending: 'En attente',
    approved: 'Approuvée',
    active: 'Active',
    completed: 'Terminée',
    cancelled: 'Annulée',
    rejected: 'Rejetée',
  };
  const label = labels[status] || defaultLabels[status] || status || '—';
  const style = styles[status] || 'bg-gray-600 text-gray-100';
  return `<span class="px-2.5 py-0.5 rounded-full text-xs font-medium ${style}">${label}</span>`;
}

/** Extrait le tableau data depuis une réponse API Laravel */
export function unwrapList(response) {
  const body = response?.data;
  if (Array.isArray(body)) return body;
  if (Array.isArray(body?.data)) return body.data;
  if (Array.isArray(body?.data?.data)) return body.data.data;
  return [];
}
