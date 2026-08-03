import api from '../api.js';
import { escapeHtml, statusBadgeClass, getQueryParam } from '../helpers.js';
import { showError, showToast } from '../notifications.js';

const basePath = '/admin/advertisements';

export const init = () => {
  const page = document.body.dataset.action;
  const id = getQueryParam('id');

  if (page === 'list') loadList();
  if (page === 'show' && id) loadShow(id);
};

const loadList = async () => {
  const tbody = document.getElementById('advertisements-table-body');
  if (!tbody) return;

  try {
    const { data } = await api.get('/admin/advertisements');
    const items = data.data ?? data;
    const list = Array.isArray(items) ? items : items.data ?? [];

    tbody.innerHTML = list.length
      ? list
          .map(
            (a) => `
      <tr class="border-b dark:border-gray-700">
        <td class="px-4 py-3">${escapeHtml(a.title ?? a.name ?? `#${a.id}`)}</td>
        <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full ${statusBadgeClass(a.status)}">${escapeHtml(a.status)}</span></td>
        <td class="px-4 py-3">
          <a href="${basePath}/show.html?id=${a.id}" class="text-blue-600 hover:underline">Voir</a>
        </td>
      </tr>`
          )
          .join('')
      : '<tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">Aucune publicité.</td></tr>';
  } catch (err) {
    showError(err);
  }
};

const loadShow = async (id) => {
  try {
    const { data } = await api.get(`/admin/advertisements/${id}`);
    const a = data.data ?? data;
    document.getElementById('ad-title')?.replaceChildren(document.createTextNode(a.title ?? a.name ?? ''));
    document.getElementById('ad-status')?.replaceChildren(document.createTextNode(a.status ?? ''));

    document.getElementById('approve-btn')?.addEventListener('click', async () => {
      await api.post(`/admin/advertisements/${id}/approve`);
      showToast('Publicité approuvée.', 'success');
      loadShow(id);
    });

    document.getElementById('reject-btn')?.addEventListener('click', async () => {
      await api.post(`/admin/advertisements/${id}/reject`);
      showToast('Publicité rejetée.', 'success');
      loadShow(id);
    });
  } catch (err) {
    showError(err);
  }
};
