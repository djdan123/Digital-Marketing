<?php

namespace App\Enums;

enum PricingType: string
{
	case FIXED = 'fixed';
	case CPM = 'cpm';
	case PER_SPOT = 'per_spot';

	public function label(): string
	{
		return match($this) {
			self::FIXED => 'Fixe',
			self::CPM => 'CPM',
			self::PER_SPOT => 'Par spot',
		};
	}
}

