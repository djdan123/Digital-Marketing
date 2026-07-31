<?php

namespace App\Enums;

enum UserRole: string
{
	case ADMIN = 'admin';
	case ADVERTISER = 'advertiser';
	case MEDIA_MANAGER = 'media_manager';
	case ACCOUNTANT = 'accountant';

	public function label(): string
	{
		return match($this) {
			self::ADMIN => 'Administrateur',
			self::ADVERTISER => 'Annonceur',
			self::MEDIA_MANAGER => 'Responsable média',
			self::ACCOUNTANT => 'Comptable',
		};
	}
}

