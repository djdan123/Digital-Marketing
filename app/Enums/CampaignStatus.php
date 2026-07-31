<?php

namespace App\Enums;

enum CampaignStatus: string
{
	case DRAFT = 'draft';
	case PENDING = 'pending';
	case APPROVED = 'approved';
	case ACTIVE = 'active';
	case COMPLETED = 'completed';
	case CANCELLED = 'cancelled';

	public function label(): string
	{
		return match($this) {
			self::DRAFT => 'Brouillon',
			self::PENDING => 'En attente',
			self::APPROVED => 'Approuvée',
			self::ACTIVE => 'Active',
			self::COMPLETED => 'Terminée',
			self::CANCELLED => 'Annulée',
		};
	}
}

