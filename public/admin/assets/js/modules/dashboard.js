import api from '../api.js';
import { formatCurrency, formatDate, escapeHtml, statusBadgeClass } from '../helpers.js';
import { showError } from '../notifications.js';

export const init = async () => {
  try {
    const { data } = await api.get('/admin/dashboard');
    const stats = data.data ?? data;

    document.getElementById('stat-users')?.replaceChildren(document.createTextNode(stats.users_count ?? stats.usersCount ?? '—'));
    document.getElementById('stat-campaigns')?.replaceChildren(document.createTextNode(stats.campaigns_count ?? stats.campaignsCount ?? '—'));
    document.getElementById('stat-media')?.replaceChildren(document.createTextNode(stats.media_count ?? stats.mediaCount ?? '—'));

    const tbody = document.getElementById('latest-campaigns-body');
    const campaigns = stats.latest_campaigns ?? stats.latestCampaigns ?? [];
    if (tbody && campaigns.length) {
      tbody.innerHTML = campaigns
        .map(
          (c) => `
        <tr class="border-b dark:border-gray-700">
          <td class="px-4 py-3">${escapeHtml(c.name)}</td>
          <td class="px-4 py-3">${escapeHtml(c.advertiser?.name ?? '—')}</td>
          <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full ${statusBadgeClass(c.status)}">${escapeHtml(c.status)}</span></td>
          <td class="px-4 py-3">${formatCurrency(c.budget)}</td>
          <td class="px-4 py-3">${formatDate(c.starts_at)} - ${formatDate(c.ends_at)}</td>
        </tr>`
        )
        .join('');
    }
  } catch (err) {
    showError(err, 'Impossible de charger le tableau de bord.');
  }
};
