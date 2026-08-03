import api from '../api.js';
import { escapeHtml, getQueryParam } from '../helpers.js';
import { showError, showToast, confirmAction } from '../notifications.js';

const basePath = '/admin/users';

export const init = () => {
  const page = document.body.dataset.action;
  const id = getQueryParam('id');

  if (page === 'list') loadList();
  if (page === 'create') bindForm();
  if (page === 'edit' && id) loadEdit(id);
};

const loadList = async () => {
  const tbody = document.getElementById('users-table-body');
  if (!tbody) return;

  try {
    const { data } = await api.get('/admin/users');
    const items = data.data ?? data;
    const list = Array.isArray(items) ? items : items.data ?? [];

    tbody.innerHTML = list.length
      ? list
          .map(
            (u) => `
      <tr class="border-b dark:border-gray-700">
        <td class="px-4 py-3">${escapeHtml(u.name)}</td>
        <td class="px-4 py-3">${escapeHtml(u.email)}</td>
        <td class="px-4 py-3 table-actions">
          <a href="${basePath}/edit.html?id=${u.id}" class="text-amber-600 hover:underline mr-2">Modifier</a>
          <button type="button" data-delete="${u.id}" class="text-red-600 hover:underline">Supprimer</button>
        </td>
      </tr>`
          )
          .join('')
      : '<tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">Aucun utilisateur.</td></tr>';

    tbody.querySelectorAll('[data-delete]').forEach((btn) => {
      btn.addEventListener('click', async () => {
        if (!confirmAction('Supprimer cet utilisateur ?')) return;
        try {
          await api.delete(`/admin/users/${btn.dataset.delete}`);
          showToast('Utilisateur supprimé.', 'success');
          loadList();
        } catch (err) {
          showError(err);
        }
      });
    });
  } catch (err) {
    showError(err);
  }
};

const bindForm = (id = null) => {
  const form = document.getElementById('user-form');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const payload = Object.fromEntries(new FormData(form));
    try {
      if (id) {
        await api.put(`/admin/users/${id}`, payload);
        showToast('Utilisateur mis à jour.', 'success');
      } else {
        showToast('Création via API register ou endpoint dédié.', 'info');
      }
      window.location.href = `${basePath}/index.html`;
    } catch (err) {
      showError(err);
    }
  });
};

const loadEdit = async (id) => {
  try {
    const { data } = await api.get(`/admin/users/${id}`);
    const u = data.data ?? data;
    const form = document.getElementById('user-form');
    Object.entries(u).forEach(([key, val]) => {
      const input = form?.querySelector(`[name="${key}"]`);
      if (input && val != null) input.value = val;
    });
    bindForm(id);
  } catch (err) {
    showError(err);
  }
};
