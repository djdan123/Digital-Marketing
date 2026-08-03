import { fetchCampaigns } from '../api/campaign';
import { formatCurrency } from '../utils/helpers';

const state = {
  page: 1,
  perPage: 10,
  query: '',
  status: '',
  meta: null,
};

const getElements = () => ({
  loader: document.getElementById('campaigns-loader'),
  error: document.getElementById('campaigns-error'),
  empty: document.getElementById('campaigns-empty'),
  tableBody: document.querySelector('#campaigns-table'),
  meta: document.getElementById('campaigns-meta'),
  prevButton: document.getElementById('campaigns-prev'),
  nextButton: document.getElementById('campaigns-next'),
  searchInput: document.getElementById('campaign-search'),
  statusSelect: document.getElementById('campaign-status'),
  refreshButton: document.getElementById('campaign-refresh-button'),
});

const renderCampaignTable = (campaigns) => {
  const tbody = document.querySelector('#campaigns-table');
  if (!tbody) return;

  tbody.innerHTML = '';

  if (!campaigns.length) {
    document.getElementById('campaigns-empty').classList.remove('hidden');
    return;
  }

  document.getElementById('campaigns-empty').classList.add('hidden');

  campaigns.forEach((campaign) => {
    const row = document.createElement('tr');
    row.className = 'hover:bg-gray-100 dark:hover:bg-gray-700';
    row.innerHTML = `
      <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">${campaign.name}</td>
      <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">${campaign.company_name || '-'}</td>
      <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">${campaign.status || '-'}</td>
      <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">${formatCurrency(campaign.budget || 0)}</td>
      <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">${campaign.start_date || '-'} → ${campaign.end_date || '-'}</td>
      <td class="px-4 py-3 space-x-2 whitespace-nowrap">
        <button data-action="edit" data-id="${campaign.id}" class="px-3 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800">Modifier</button>
        <button data-action="delete" data-id="${campaign.id}" class="px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">Supprimer</button>
      </td>
    `;
    tbody.appendChild(row);
  });
};

const renderMeta = () => {
  const { meta } = state;
  const metaEl = document.getElementById('campaigns-meta');
  if (!meta || !metaEl) return;

  metaEl.textContent = `Page ${meta.current_page} sur ${meta.last_page} — ${meta.total} campagnes`;
};

const updateNavigation = () => {
  const { meta } = state;
  const prevButton = document.getElementById('campaigns-prev');
  const nextButton = document.getElementById('campaigns-next');

  if (!meta) return;

  prevButton.disabled = !meta.prev_page_url;
  nextButton.disabled = !meta.next_page_url;
};

const loadCampaigns = async () => {
  const { loader, error, searchInput, statusSelect } = getElements();

  loader.classList.remove('hidden');
  error.classList.add('hidden');
  document.getElementById('campaigns-empty').classList.add('hidden');

  try {
    const data = await fetchCampaigns({
      page: state.page,
      per_page: state.perPage,
      search: state.query,
      status: state.status,
    });

    renderCampaignTable(data.data || []);
    state.meta = data.meta;
    renderMeta();
    updateNavigation();
  } catch (err) {
    error.classList.remove('hidden');
    error.textContent = err.response?.data?.message || 'Erreur lors du chargement des campagnes.';
  } finally {
    loader.classList.add('hidden');
  }
};

const initEvents = () => {
  const { prevButton, nextButton, searchInput, statusSelect, refreshButton } = getElements();

  prevButton.addEventListener('click', () => {
    if (state.page > 1) {
      state.page -= 1;
      loadCampaigns();
    }
  });

  nextButton.addEventListener('click', () => {
    if (state.meta?.current_page < state.meta?.last_page) {
      state.page += 1;
      loadCampaigns();
    }
  });

  searchInput.addEventListener('input', (event) => {
    state.query = event.target.value;
    state.page = 1;
    loadCampaigns();
  });

  statusSelect.addEventListener('change', (event) => {
    state.status = event.target.value;
    state.page = 1;
    loadCampaigns();
  });

  refreshButton.addEventListener('click', () => {
    loadCampaigns();
  });
};

if (document.querySelector('#campaigns-page')) {
  initEvents();
  loadCampaigns();
}
