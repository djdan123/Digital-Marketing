// =============================================
// Gestion de l'authentification
// =============================================

import api from './api.js';

/**
 * Connexion
 */
export async function login(email, password) {
    try {
        const response = await api.post('/login', {
            email: email,
            password: password,
            device_name: 'admin-dashboard'
        });

        // Selon la structure de ta réponse API
        const token = response.data.token;
        const user = response.data.user;

        // Sauvegarde
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(user));

        return { success: true, user };
    } catch (error) {
        const message = error.response?.data?.message 
            || error.response?.data?.errors?.email?.[0]
            || 'Identifiants incorrects';

        return { success: false, message };
    }
}

/**
 * Déconnexion
 */
export async function logout() {
    try {
        await api.post('/logout');
    } catch (error) {
        // Même en cas d'erreur, on nettoie le localStorage
    } finally {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/admin/login.html';
    }
}

/**
 * Récupère l'utilisateur connecté
 */
export function getUser() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
}

/**
 * Vérifie si l'utilisateur est connecté
 */
export function isAuthenticated() {
    return !!localStorage.getItem('token');
}

/**
 * Protection des pages (à appeler en haut de chaque page)
 */
export function requireAuth() {
    if (!isAuthenticated()) {
        window.location.href = '/admin/login.html';
    }
}

/**
 * Récupère le rôle de l'utilisateur
 */
export function getUserRole() {
    const user = getUser();
    return user?.role || null;
}