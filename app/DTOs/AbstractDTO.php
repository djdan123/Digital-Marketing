<?php

namespace App\DTOs;

abstract readonly class AbstractDTO
{
    /**
     * Retourne une représentation tableau des données du DTO.
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
