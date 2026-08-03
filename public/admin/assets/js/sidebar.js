/**
 * Sidebar dynamique selon le rôle utilisateur
 */
import { getUser, logout } from './auth.js';

const NAV_ITEMS = [
  { href: '/admin/index.html', label: 'Dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', roles: ['admin'] },
  { href: '/admin/campaigns/index.html', label: 'Campagnes', roles: ['admin'] },
  { href: '/admin/advertisements/index.html', label: 'Publicités', roles: ['admin'] },
  { href: '/admin/media/index.html', label: 'Médias', roles: ['admin'] },
  { href: '/admin/schedules/index.html', label: 'Planifications', roles: ['admin', 'media-manager'] },
  { href: '/admin/broadcasts/index.html', label: 'Diffusions', roles: ['admin', 'media-manager'] },
  { href: '/admin/payments/index.html', label: 'Paiements', roles: ['admin'] },
  { href: '/admin/invoices/index.html', label: 'Factures', roles: ['admin'] },
  { href: '/admin/users/index.html', label: 'Utilisateurs', roles: ['admin'] },
  { href: '/admin/roles/index.html', label: 'Rôles', roles: ['admin'] },
  { href: '/admin/statistics/index.html', label: 'Statistiques', roles: ['admin'] },
  { href: '/admin/reports/index.html', label: 'Rapports', roles: ['admin'] },
  { href: '/admin/settings/index.html', label: 'Paramètres', roles: ['admin'] },
];

const getUserRoles = () => {
  const user = getUser();
  if (!user) return [];
  if (Array.isArray(user.roles)) {
    return user.roles.map((r) => (typeof r === 'string' ? r : r.name));
  }
  return user.role ? [user.role] : ['admin'];
};

const isActive = (href) => {
  const path = window.location.pathname;
  if (href === '/admin/index.html') {
    return path.endsWith('/admin/index.html') || path.endsWith('/admin/');
  }
  return path.startsWith(href.replace('/index.html', ''));
};

export const renderSidebarNav = (container) => {
  if (!container) return;

  const roles = getUserRoles();
  const items = NAV_ITEMS.filter(
    (item) => !item.roles || item.roles.some((r) => roles.includes(r))
  );

  container.innerHTML = items
    .map(
      (item) => `
    <li>
      <a href="${item.href}" class="sidebar-link flex items-center p-2 rounded-lg text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 ${isActive(item.href) ? 'active bg-gray-100 dark:bg-gray-700' : ''}">
        <span>${item.label}</span>
      </a>
    </li>`
    )
    .join('');
};

export const initSidebarToggle = () => {
  const toggle = document.getElementById('sidebar-toggle');
  const sidebar = document.getElementById('sidebar');
  const backdrop = document.getElementById('sidebar-backdrop');

  toggle?.addEventListener('click', () => {
    sidebar?.classList.toggle('-translate-x-full');
    backdrop?.classList.toggle('hidden');
  });

  backdrop?.addEventListener('click', () => {
    sidebar?.classList.add('-translate-x-full');
    backdrop?.classList.add('hidden');
  });

  document.getElementById('logout-btn')?.addEventListener('click', (e) => {
    e.preventDefault();
    logout();
  });
};

export const updateNavbarUser = () => {
  const user = getUser();
  const nameEl = document.getElementById('navbar-user-name');
  const avatarEl = document.getElementById('navbar-user-avatar');
  if (nameEl && user) {
    nameEl.textContent = user.name ?? user.email ?? 'Admin';
  }
  if (avatarEl && user?.avatar_url) {
    avatarEl.src = user.avatar_url;
  }
};
