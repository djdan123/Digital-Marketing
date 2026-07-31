<?php

namespace App\Enums;

enum CampaignType: string
{
	case STANDARD = 'standard';
	case PROMOTIONAL = 'promotional';
	case BRANDING = 'branding';
	case AUTOMATED = 'automated';

	public function label(): string
	{
		return match($this) {
			self::STANDARD => 'Standard',
			self::PROMOTIONAL => 'Promotionnelle',
			self::BRANDING => 'Branding',
			self::AUTOMATED => 'Automatisée',
		};
	}
}

