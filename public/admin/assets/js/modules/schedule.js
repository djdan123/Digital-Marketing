import api from '../api.js';
import { escapeHtml, formatDateTime, getQueryParam } from '../helpers.js';
import { showError, showToast } from '../notifications.js';

const basePath = '/admin/schedules';

export const init = () => {
  const page = document.body.dataset.action;
  const id = getQueryParam('id');

  if (page === 'list') loadList();
  if (page === 'create') bindForm();
  if (page === 'edit' && id) loadEdit(id);
};

const loadList = async () => {
  const tbody = document.getElementById('schedules-table-body');
  if (!tbody) return;

  try {
    const { data } = await api.get('/media-manager/schedules');
    const list = data.data ?? data;
    const items = Array.isArray(list) ? list : list.data ?? [];

    tbody.innerHTML = items.length
      ? items
          .map(
            (s) => `
      <tr class="border-b dark:border-gray-700">
        <td class="px-4 py-3">${escapeHtml(String(s.id))}</td>
        <td class="px-4 py-3">${formatDateTime(s.scheduled_at ?? s.starts_at)}</td>
        <td class="px-4 py-3">
          <a href="${basePath}/edit.html?id=${s.id}" class="text-amber-600 hover:underline">Modifier</a>
        </td>
      </tr>`
          )
          .join('')
      : '<tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">Aucune planification.</td></tr>';
  } catch (err) {
    showError(err);
  }
};

const bindForm = (id = null) => {
  const form = document.getElementById('schedule-form');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const payload = Object.fromEntries(new FormData(form));
    try {
      if (id) {
        await api.put(`/media-manager/schedules/${id}`, payload);
        showToast('Planification mise à jour.', 'success');
      } else {
        await api.post('/media-manager/schedules', payload);
        showToast('Planification créée.', 'success');
      }
      window.location.href = `${basePath}/index.html`;
    } catch (err) {
      showError(err);
    }
  });
};

const loadEdit = async (id) => {
  try {
    const { data } = await api.get(`/media-manager/schedules/${id}`);
    const s = data.data ?? data;
    const form = document.getElementById('schedule-form');
    Object.entries(s).forEach(([key, val]) => {
      const input = form?.querySelector(`[name="${key}"]`);
      if (input && val != null) input.value = val;
    });
    bindForm(id);
  } catch (err) {
    showError(err);
  }
};
