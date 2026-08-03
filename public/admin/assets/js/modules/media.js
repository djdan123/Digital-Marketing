import api from '../api.js';
import { escapeHtml, getQueryParam } from '../helpers.js';
import { showError, showToast, confirmAction } from '../notifications.js';

const basePath = '/admin/media';

export const init = () => {
  const page = document.body.dataset.action;
  const id = getQueryParam('id');

  if (page === 'list') loadList();
  if (page === 'create') bindForm();
  if (page === 'edit' && id) loadEdit(id);
  if (page === 'show' && id) loadShow(id);
};

const loadList = async () => {
  const tbody = document.getElementById('media-table-body');
  if (!tbody) return;

  try {
    const { data } = await api.get('/admin/media');
    const items = data.data ?? data;
    const list = Array.isArray(items) ? items : items.data ?? [];

    tbody.innerHTML = list.length
      ? list
          .map(
            (m) => `
      <tr class="border-b dark:border-gray-700">
        <td class="px-4 py-3">${escapeHtml(m.name)}</td>
        <td class="px-4 py-3">${escapeHtml(m.type ?? '—')}</td>
        <td class="px-4 py-3 table-actions">
          <a href="${basePath}/show.html?id=${m.id}" class="text-blue-600 hover:underline mr-2">Voir</a>
          <a href="${basePath}/edit.html?id=${m.id}" class="text-amber-600 hover:underline mr-2">Modifier</a>
          <button type="button" data-delete="${m.id}" class="text-red-600 hover:underline">Supprimer</button>
        </td>
      </tr>`
          )
          .join('')
      : '<tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">Aucun média.</td></tr>';

    tbody.querySelectorAll('[data-delete]').forEach((btn) => {
      btn.addEventListener('click', async () => {
        if (!confirmAction('Supprimer ce média ?')) return;
        try {
          await api.delete(`/admin/media/${btn.dataset.delete}`);
          showToast('Média supprimé.', 'success');
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
  const form = document.getElementById('media-form');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const payload = Object.fromEntries(new FormData(form));
    try {
      if (id) {
        await api.put(`/admin/media/${id}`, payload);
        showToast('Média mis à jour.', 'success');
      } else {
        await api.post('/admin/media', payload);
        showToast('Média créé.', 'success');
      }
      window.location.href = `${basePath}/index.html`;
    } catch (err) {
      showError(err);
    }
  });
};

const loadEdit = async (id) => {
  try {
    const { data } = await api.get(`/admin/media/${id}`);
    const m = data.data ?? data;
    const form = document.getElementById('media-form');
    Object.entries(m).forEach(([key, val]) => {
      const input = form?.querySelector(`[name="${key}"]`);
      if (input && val != null) input.value = val;
    });
    bindForm(id);
  } catch (err) {
    showError(err);
  }
};

const loadShow = async (id) => {
  try {
    const { data } = await api.get(`/admin/media/${id}`);
    const m = data.data ?? data;
    document.getElementById('media-name')?.replaceChildren(document.createTextNode(m.name ?? ''));
    document.getElementById('media-type')?.replaceChildren(document.createTextNode(m.type ?? ''));
  } catch (err) {
    showError(err);
  }
};
