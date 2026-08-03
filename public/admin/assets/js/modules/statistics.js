import api from '../api.js';
import { showError } from '../notifications.js';

export const init = async () => {
  const container = document.getElementById('statistics-container');
  if (!container) return;

  try {
    const { data } = await api.get('/shared/statistics');
    const stats = data.data ?? data;
    const list = Array.isArray(stats) ? stats : stats.data ?? [];

    container.innerHTML = list.length
      ? `<pre class="text-sm overflow-auto p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">${JSON.stringify(list, null, 2)}</pre>`
      : '<p class="text-gray-500">Aucune statistique disponible.</p>';
  } catch (err) {
    showError(err);
  }
};
