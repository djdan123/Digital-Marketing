import { getUser } from '../utils/auth';

const permissions = {
  super_admin: ['dashboard', 'campaigns', 'advertisements', 'media', 'schedules', 'broadcasts', 'payments', 'statistics', 'users', 'settings'],
  admin: ['dashboard', 'campaigns', 'advertisements', 'media', 'schedules', 'broadcasts', 'payments', 'statistics'],
  annonceur: ['dashboard', 'campaigns', 'advertisements', 'payments'],
  radio_manager: ['dashboard', 'media', 'schedules', 'broadcasts'],
  tv_manager: ['dashboard', 'media', 'schedules', 'broadcasts'],
};

export const initSidebar = () => {
  const user = getUser();
  if (!user) return;

  document.querySelectorAll('[data-role-item]').forEach((el) => {
    const item = el.dataset.roleItem;
    const allowed = permissions[user.role] || [];
    el.classList.toggle('hidden', !allowed.includes(item));
  });
};
