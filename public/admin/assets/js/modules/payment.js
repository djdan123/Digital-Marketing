import api from '../api.js';
import { formatCurrency, escapeHtml, getQueryParam } from '../helpers.js';
import { showError } from '../notifications.js';

export const init = () => {
  const page = document.body.dataset.action;
  const id = getQueryParam('id');

  if (page === 'list') loadList();
  if (page === 'show' && id) loadShow(id);
};

const loadList = async () => {
  const tbody = document.getElementById('payments-table-body');
  if (!tbody) return;

  try {
    const { data } = await api.get('/payments');
    const items = data.data ?? data;
    const list = Array.isArray(items) ? items : items.data ?? [];

    tbody.innerHTML = list.length
      ? list
          .map(
            (p) => `
      <tr class="border-b dark:border-gray-700">
        <td class="px-4 py-3">${escapeHtml(String(p.id))}</td>
        <td class="px-4 py-3">${formatCurrency(p.amount)}</td>
        <td class="px-4 py-3">${escapeHtml(p.status ?? '—')}</td>
        <td class="px-4 py-3">
          <a href="/admin/payments/show.html?id=${p.id}" class="text-blue-600 hover:underline">Voir</a>
        </td>
      </tr>`
          )
          .join('')
      : '<tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Aucun paiement.</td></tr>';
  } catch (err) {
    showError(err);
  }
};

const loadShow = async (id) => {
  try {
    const { data } = await api.get(`/payments/${id}`);
    const p = data.data ?? data;
    document.getElementById('payment-amount')?.replaceChildren(document.createTextNode(formatCurrency(p.amount)));
    document.getElementById('payment-status')?.replaceChildren(document.createTextNode(p.status ?? ''));
  } catch (err) {
    showError(err);
  }
};
