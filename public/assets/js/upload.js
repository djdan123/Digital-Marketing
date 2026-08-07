import api from './api.js';

/**
 * Types autorisés et tailles max (en Mo)
 */
export const UPLOAD_CONFIG = {
    image: {
        accept: 'image/jpeg,image/png,image/webp,image/gif',
        maxSizeMB: 5,
        label: 'Image (JPG, PNG, WebP – max 5 Mo)'
    },
    audio: {
        accept: 'audio/mpeg,audio/wav,audio/mp3,audio/x-wav,audio/ogg',
        maxSizeMB: 20,
        label: 'Audio (MP3, WAV, OGG – max 20 Mo)'
    },
    video: {
        accept: 'video/mp4,video/webm,video/quicktime',
        maxSizeMB: 100,
        label: 'Vidéo (MP4, WebM – max 100 Mo)'
    }
};

/**
 * Valide un fichier selon le type de contenu
 */
export function validateFile(file, contentType) {
    const config = UPLOAD_CONFIG[contentType];
    if (!config) return { ok: false, error: 'Type de contenu invalide' };

    if (!file) return { ok: false, error: 'Aucun fichier sélectionné' };

    const maxBytes = config.maxSizeMB * 1024 * 1024;
    if (file.size > maxBytes) {
        return { ok: false, error: `Fichier trop volumineux (max ${config.maxSizeMB} Mo)` };
    }

    // Vérifier l'extension / mime
    const allowed = config.accept.split(',');
    const mimeOk = allowed.some(m => file.type === m || file.type.startsWith(m.split('/')[0] + '/'));
    if (!mimeOk && file.type) {
        return { ok: false, error: `Format non autorisé. ${config.label}` };
    }

    return { ok: true };
}

/**
 * Upload un fichier vers l'API
 * @returns {Promise<{url, path, id, ...}>}
 */
export async function uploadFile(file, contentType, onProgress = null) {
    const validation = validateFile(file, contentType);
    if (!validation.ok) {
        throw new Error(validation.error);
    }

    const formData = new FormData();
    formData.append('file', file);
    formData.append('type', contentType); // image | audio | video

    const response = await api.post('/advertiser/uploads', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        },
        onUploadProgress: (event) => {
            if (onProgress && event.total) {
                const percent = Math.round((event.loaded * 100) / event.total);
                onProgress(percent);
            }
        }
    });

    // Adapter selon la réponse de ton backend
    return response.data.data || response.data;
}

/**
 * Upload avec fallback si l'endpoint n'existe pas encore
 */
export async function uploadFileSafe(file, contentType, onProgress = null) {
    try {
        return await uploadFile(file, contentType, onProgress);
    } catch (error) {
        // Fallback : essayer /uploads ou /media/upload
        if (error.response?.status === 404) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', contentType);

            const res = await api.post('/uploads', formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
                onUploadProgress: (event) => {
                    if (onProgress && event.total) {
                        onProgress(Math.round((event.loaded * 100) / event.total));
                    }
                }
            });
            return res.data.data || res.data;
        }
        throw error;
    }
}