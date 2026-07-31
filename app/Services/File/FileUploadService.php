<?php

namespace App\Services\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Téléverse un fichier et retourne le chemin stocké.
     */
    public function upload(UploadedFile $file, string $directory = 'uploads', ?string $disk = null): string
    {
        $disk = $disk ?? config('filesystems.default');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, $disk);
        return $path;
    }

    /**
     * Supprime un fichier.
     */
    public function delete(string $path, ?string $disk = null): bool
    {
        $disk = $disk ?? config('filesystems.default');
        return Storage::disk($disk)->delete($path);
    }

    /**
     * Récupère l'URL publique d'un fichier.
     */
    public function url(string $path, ?string $disk = null): string
    {
        $disk = $disk ?? config('filesystems.default');
        return Storage::disk($disk)->url($path);
    }

    /**
     * Vérifie le type MIME autorisé.
     */
    public function validateMime(UploadedFile $file, array $allowedMimes): bool
    {
        return in_array($file->getClientMimeType(), $allowedMimes);
    }

    /**
     * Récupère les métadonnées d'un fichier audio/vidéo (durée, etc.)
     * Utiliser une bibliothèque comme getID3 ou FFmpeg.
     */
    public function getMediaInfo(string $path): array
    {
        // Placeholder – à implémenter avec un package externe
        return [
            'duration' => 0,
            'bitrate'  => 0,
        ];
    }
}