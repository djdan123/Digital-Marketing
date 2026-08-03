// =============================================
// Configuration Axios pour TruckAll
// =============================================

const API_BASE_URL = 'http://127.0.0.1:8000/api';  // Change si besoin (ex: production)

const api = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    }
});

// Intercepteur : ajoute automatiquement le token Sanctum
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

// Intercepteur : gère les erreurs globales
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response) {
            // Token expiré ou non authentifié
            if (error.response.status === 401) {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                window.location.href = '/admin/login.html';
            }

            // Erreur de validation
            if (error.response.status === 422) {
                console.error('Erreur de validation :', error.response.data.errors);
            }
        }
        return Promise.reject(error);
    }
);

export default api;