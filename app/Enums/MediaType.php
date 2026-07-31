<?php

namespace App\Enums;

enum MediaType: string
{
	case RADIO = 'radio';
	case TELEVISION = 'television';
	case WEB = 'web';
	case SOCIAL = 'social';
	case LED = 'led';

	public function label(): string
	{
		return match($this) {
			self::RADIO => 'Radio',
			self::TELEVISION => 'Télévision',
			self::WEB => 'Web',
			self::SOCIAL => 'Réseaux sociaux',
			self::LED => 'Panneau LED',
		};
	}
}

