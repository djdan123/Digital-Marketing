<?php

namespace App\Enums;

enum AdvertisementStatus: string
{
	case DRAFT = 'draft';
	case PENDING = 'pending';
	case APPROVED = 'approved';
	case REJECTED = 'rejected';
	case SCHEDULED = 'scheduled';
	case COMPLETED = 'completed';

	public function label(): string
	{
		return match($this) {
			self::DRAFT => 'Brouillon',
			self::PENDING => 'En attente',
			self::APPROVED => 'Approuvée',
			self::REJECTED => 'Refusée',
			self::SCHEDULED => 'Planifiée',
			self::COMPLETED => 'Terminée',
		};
	}
}

