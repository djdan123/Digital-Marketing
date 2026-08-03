import { login } from '../api/auth';

const form = document.querySelector('#login-form');
const errorMessage = document.querySelector('#login-error');

const getApiErrorMessage = (error) => {
  if (!error?.response) {
    return 'Erreur de connexion, vérifiez votre réseau.';
  }

  const data = error.response.data;
  if (data.message) {
    return data.message;
  }

  if (data.errors) {
    return Object.values(data.errors).flat()[0];
  }

  return 'Échec de la connexion.';
};

if (form) {
  form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const email = form.querySelector('[name=email]').value;
    const password = form.querySelector('[name=password]').value;
    const landingPage = '/crud/campaigns/';

    try {
      await login({ email, password });
      window.location.href = landingPage;
    } catch (error) {
      errorMessage.classList.remove('hidden');
      errorMessage.textContent = getApiErrorMessage(error);
    }
  });
}
