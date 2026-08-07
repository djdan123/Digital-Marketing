import api from './api.js';

export function getToken() {
    return localStorage.getItem('token');
}

export function getUser() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
}

export function setToken(token) {
    localStorage.setItem('token', token);
}

export function setUser(user) {
    localStorage.setItem('user', JSON.stringify(user));
}

export function isAuthenticated() {
    return !!getToken();
}

export function getUserRole() {
    const user = getUser();
    return user?.role || null;
}

/** Vérifie auth + rôle admin uniquement */
export function requireAdmin() {
    if (!isAuthenticated()) {
        window.location.href = '/admin/login.html';
        return false;
    }

    const role = getUserRole();
    // Accepter uniquement admin
    if (role !== 'admin') {
        // Rediriger le client / media manager vers leur espace
        if (role === 'advertiser' || role === 'annonceur') {
            window.location.href = '/client/dashboard.html';
        } else if (role === 'media_manager' || role === 'mediamanager') {
            window.location.href = '/mediamanager/index.html'; // ou /media/dashboard.html plus tard
        } else {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/admin/login.html';
        }
        return false;
    }
    return true;
}

/** Alias pour compatibilité avec les pages existantes */
export function requireAuth() {
    return requireAdmin();
}

export function redirectIfAuthenticated() {
    if (!isAuthenticated()) return;

    const role = getUserRole();
    if (role === 'admin') {
        window.location.href = '/admin/index.html';
    } else if (role === 'advertiser' || role === 'annonceur') {
        window.location.href = '/client/dashboard.html';
    } else {
        window.location.href = '/mediamanager/index.html';
    }
}

export async function login(email, password) {
    try {
        const response = await api.post('/login', { email, password });
        const data = response.data;

        const token = data.token || data.access_token || data.data?.token;
        const user = data.user || data.data?.user;

        if (!token || !user) {
            return { success: false, message: 'Réponse invalide du serveur' };
        }

        setToken(token);
        setUser(user);

        return { success: true, user, token };
    } catch (error) {
        const validationErrors = error.response?.data?.errors;
        const backendMessage = error.response?.data?.message;
        const status = error.response?.status;

        if (validationErrors && typeof validationErrors === 'object') {
            const firstError = Object.values(validationErrors).flat().find(Boolean);
            return { success: false, message: firstError || backendMessage || 'Email ou mot de passe incorrect' };
        }

        if (status === 401 || status === 403 || status === 422) {
            return { success: false, message: backendMessage || 'Email ou mot de passe incorrect' };
        }

        return {
            success: false,
            message: backendMessage || error.message || 'Email ou mot de passe incorrect'
        };
    }
}

export async function logout() {
    try {
        await api.post('/logout');
    } catch (e) {}
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/admin/login.html';
}