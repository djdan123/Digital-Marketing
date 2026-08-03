import api from '../api.js';
import { formatCurrency, formatDate, escapeHtml, statusBadgeClass, getQueryParam } from '../helpers.js';
import { showError, showToast, confirmAction } from '../notifications.js';

const basePath = '/admin/campaigns';

const renderList = (campaigns) => {
  const tbody = document.getElementById('campaigns-table-body');
  if (!tbody) return;

  if (!campaigns.length) {
    tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucune campagne.</td></tr>';
    return;
  }

  tbody.innerHTML = campaigns
    .map(
      (c) => `
    <tr class="border-b dark:border-gray-700">
      <td class="px-4 py-3">${escapeHtml(c.name)}</td>
      <td class="px-4 py-3">${escapeHtml(c.advertiser?.name ?? '—')}</td>
      <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full ${statusBadgeClass(c.status)}">${escapeHtml(c.status)}</span></td>
      <td class="px-4 py-3">${formatCurrency(c.budget)}</td>
      <td class="px-4 py-3">${formatDate(c.starts_at)} - ${formatDate(c.ends_at)}</td>
      <td class="px-4 py-3 table-actions">
        <a href="${basePath}/show.html?id=${c.id}" class="text-blue-600 hover:underline mr-2">Voir</a>
        <a href="${basePath}/edit.html?id=${c.id}" class="text-amber-600 hover:underline mr-2">Modifier</a>
        <button type="button" data-delete="${c.id}" class="text-red-600 hover:underline">Supprimer</button>
      </td>
    </tr>`
    )
    .join('');

  tbody.querySelectorAll('[data-delete]').forEach((btn) => {
    btn.addEventListener('click', async () => {
      if (!confirmAction('Supprimer cette campagne ?')) return;
      try {
        await api.delete(`/admin/campaigns/${btn.dataset.delete}`);
        showToast('Campagne supprimée.', 'success');
        loadList();
      } catch (err) {
        showError(err);
      }
    });
  });
};

const loadList = async () => {
  try {
    const { data } = await api.get('/admin/campaigns');
    const items = data.data ?? data;
    renderList(Array.isArray(items) ? items : items.data ?? []);
  } catch (err) {
    showError(err);
  }
};

const bindForm = (method = 'post', id = null) => {
  const form = document.getElementById('campaign-form');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const payload = Object.fromEntries(new FormData(form));
    try {
      if (id) {
        await api.put(`/admin/campaigns/${id}`, payload);
        showToast('Campagne mise à jour.', 'success');
      } else {
        await api.post('/admin/campaigns', payload);
        showToast('Campagne créée.', 'success');
      }
      window.location.href = `${basePath}/index.html`;
    } catch (err) {
      showError(err);
    }
  });
};

const loadShow = async (id) => {
  try {
    const { data } = await api.get(`/admin/campaigns/${id}`);
    const c = data.data ?? data;
    document.getElementById('campaign-name')?.replaceChildren(document.createTextNode(c.name ?? ''));
    document.getElementById('campaign-status')?.replaceChildren(document.createTextNode(c.status ?? ''));
    document.getElementById('campaign-budget')?.replaceChildren(document.createTextNode(formatCurrency(c.budget)));
  } catch (err) {
    showError(err);
  }
};

const loadEdit = async (id) => {
  try {
    const { data } = await api.get(`/admin/campaigns/${id}`);
    const c = data.data ?? data;
    const form = document.getElementById('campaign-form');
    if (!form) return;
    Object.entries(c).forEach(([key, val]) => {
      const input = form.querySelector(`[name="${key}"]`);
      if (input && val != null) input.value = val;
    });
    bindForm('put', id);
  } catch (err) {
    showError(err);
  }
};

export const init = () => {
  const page = document.body.dataset.action;
  const id = getQueryParam('id');

  if (page === 'list') loadList();
  if (page === 'create') bindForm();
  if (page === 'edit' && id) loadEdit(id);
  if (page === 'show' && id) loadShow(id);
};
