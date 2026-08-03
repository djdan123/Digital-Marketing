import axios from 'axios';
import { getToken, logout } from './auth';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
});

api.interceptors.request.use((config) => {
  const token = getToken();
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    const status = error.response?.status;

    if (status === 401) {
      logout();
      window.location.href = '/authentication/sign-in/';
    }

    if (status === 403) {
      window.location.href = '/pages/500/';
    }

    return Promise.reject(error);
  }
);

export default api;
