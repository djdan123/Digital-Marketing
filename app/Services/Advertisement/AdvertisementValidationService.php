<?php

namespace App\Services\Advertisement;

use App\Models\Advertisement;
use App\Models\Campaign;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdvertisementValidationService
{
    /**
     * Valide le contenu d'une annonce avant soumission.
     * Vérifie les règles métier (format, taille, durée, etc.)
     */
    public function validate(Advertisement $advertisement): void
    {
        $rules = [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'duration'    => 'required|integer|min:5|max:300', // 5 sec à 5 min
            'file_path'   => 'required|string',
        ];

        $validator = Validator::make($advertisement->toArray(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Vérification supplémentaire : le format doit correspondre au type de média
        $media = $advertisement->media;
        if ($media) {
            $this->checkFormatCompatibility($advertisement, $media);
        }
    }

    private function checkFormatCompatibility(Advertisement $ad, $media): void
    {
        $allowedFormats = match ($media->type) {
            'radio'    => ['audio/mpeg', 'audio/wav', 'audio/aac'],
            'television'=> ['video/mp4', 'video/quicktime', 'video/x-msvideo'],
            'web'      => ['image/png', 'image/jpeg', 'image/webp', 'video/mp4'],
            'social'   => ['image/*', 'video/*'],
            default    => ['*'],
        };

        // Simule la vérification du MIME du fichier (à adapter avec le stockage)
        // Ici on suppose que le champ 'format' contient le MIME
        if (!in_array($ad->format, $allowedFormats) && $allowedFormats !== ['*']) {
            throw new \Exception('Le format du fichier n\'est pas compatible avec ce média.');
        }
    }
}