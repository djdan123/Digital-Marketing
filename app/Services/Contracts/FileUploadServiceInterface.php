<?php

namespace App\Services\Contracts;

use Illuminate\Http\UploadedFile;

interface FileUploadServiceInterface extends ServiceInterface
{
    /**
     * Téléverse un fichier et retourne son chemin.
     */
    public function upload(UploadedFile $file, string $directory = 'uploads', ?string $disk = null): string;

    /**
     * Supprime un fichier.
     */
    public function delete(string $path, ?string $disk = null): bool;

    /**
     * Retourne l'URL publique d'un fichier.
     */
    public function url(string $path, ?string $disk = null): string;

    /**
     * Vérifie si le MIME du fichier est autorisé.
     */
    public function validateMime(UploadedFile $file, array $allowedMimes): bool;

    /**
     * Récupère les métadonnées d'un fichier média (durée, bitrate...).
     */
    public function getMediaInfo(string $path): array;
}