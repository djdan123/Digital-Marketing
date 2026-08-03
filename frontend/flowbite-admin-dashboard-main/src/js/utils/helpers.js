export const formatCurrency = (value) => {
  if (typeof value !== 'number') {
    value = Number(value) || 0;
  }
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR',
  }).format(value);
};

export const createElementFromHtml = (htmlString) => {
  const div = document.createElement('div');
  div.innerHTML = htmlString.trim();
  return div.firstChild;
};
