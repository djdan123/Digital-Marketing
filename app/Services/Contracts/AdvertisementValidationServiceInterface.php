<?php

namespace App\Services\Contracts;

use App\Models\Advertisement;

interface AdvertisementValidationServiceInterface extends ServiceInterface
{
    /**
     * Valide le contenu d'une annonce avant soumission.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(Advertisement $advertisement): void;
}