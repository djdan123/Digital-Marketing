import { requireAuth, logout, getUser } from '/assets/js/auth.js';
import api from '/assets/js/api.js';

export async function initMediaManagerPage() {
    requireAuth();

    const user = getUser();
    if (!user || user.role !== 'media_manager') {
        window.location.href = '/auth/login.html';
        return false;
    }

    await loadSidebar();
    return true;
}

export async function loadSidebar() {
    const container = document.getElementById('sidebar-container');
    if (!container) return;

    try {
        const response = await fetch('/layouts/sidebar-media.html');
        if (!response.ok) throw new Error('Sidebar introuvable');
        const html = await response.text();
        container.innerHTML = html;

        document.getElementById('logout-btn')?.addEventListener('click', (event) => {
            event.preventDefault();
            logout();
        });

        const currentPath = window.location.pathname.split('/').pop() || 'index.html';
        document.querySelectorAll('[data-nav-link]').forEach((link) => {
            const target = link.getAttribute('data-nav-link');
            if (target === currentPath) {
                link.classList.add('bg-slate-800', 'text-white');
            }
        });

        updatePendingBadge();
    } catch (error) {
        container.innerHTML = '<div class="rounded-xl border border-red-700/40 bg-red-950/50 p-4 text-sm text-red-300">Impossible de charger la sidebar.</div>';
    }
}

export async function updatePendingBadge() {
    const badge = document.getElementById('pending-badge');
    if (!badge) return;

    try {
        const response = await api.get('/media-manager/requests', { params: { status: 'pending' } });
        const requests = response.data?.data || response.data || [];
        const count = Array.isArray(requests) ? requests.length : 0;
        badge.textContent = count > 0 ? count : '0';
        badge.classList.toggle('hidden', count === 0);
    } catch (error) {
        badge.textContent = '0';
        badge.classList.add('hidden');
    }
}

export function formatDate(value) {
    if (!value) return '—';
    try {
        return new Date(value).toLocaleString('fr-FR', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (error) {
        return value;
    }
}

export function getStatusBadgeClass(status) {
    const map = {
        pending: 'bg-yellow-500/15 text-yellow-300 border-yellow-500/30',
        in_discussion: 'bg-blue-500/15 text-blue-300 border-blue-500/30',
        accepted: 'bg-blue-500/15 text-blue-300 border-blue-500/30',
        scheduled: 'bg-sky-500/15 text-sky-300 border-sky-500/30',
        completed: 'bg-green-500/15 text-green-300 border-green-500/30',
        rejected: 'bg-red-500/15 text-red-300 border-red-500/30',
        failed: 'bg-red-500/15 text-red-300 border-red-500/30',
        cancelled: 'bg-slate-500/15 text-slate-300 border-slate-500/30',
        in_progress: 'bg-purple-500/15 text-purple-300 border-purple-500/30'
    };
    return map[status] || 'bg-slate-500/15 text-slate-300 border-slate-500/30';
}

export function getStatusLabel(status) {
    const labels = {
        pending: 'En attente',
        in_discussion: 'En discussion',
        accepted: 'Acceptée',
        scheduled: 'Planifiée',
        completed: 'Terminée',
        rejected: 'Refusée',
        failed: 'Échouée',
        cancelled: 'Annulée',
        in_progress: 'En cours'
    };
    return labels[status] || status || 'Inconnu';
}

export function createEmptyState(message) {
    return `<div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 text-sm text-slate-400">${message}</div>`;
}
