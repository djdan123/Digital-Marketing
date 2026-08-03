/**
 * Notifications toast (Flowbite) et alertes
 */

export const showToast = (message, type = 'info') => {
  const colors = {
    success: 'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200',
    error: 'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200',
    warning: 'text-yellow-500 bg-yellow-100 dark:bg-yellow-800 dark:text-yellow-200',
    info: 'text-blue-500 bg-blue-100 dark:bg-blue-800 dark:text-blue-200',
  };

  const toast = document.createElement('div');
  toast.className = `fixed top-20 right-4 z-50 flex items-center w-full max-w-xs p-4 rounded-lg shadow ${colors[type] ?? colors.info}`;
  toast.setAttribute('role', 'alert');
  toast.innerHTML = `<span class="text-sm font-normal">${message}</span>`;
  document.body.appendChild(toast);

  setTimeout(() => toast.remove(), 4000);
};

export const confirmAction = (message) => window.confirm(message);

export const showError = (error, fallback = 'Une erreur est survenue.') => {
  const data = error?.response?.data;
  let msg = fallback;
  if (data?.message) msg = data.message;
  else if (data?.errors) msg = Object.values(data.errors).flat()[0];
  showToast(msg, 'error');
};
