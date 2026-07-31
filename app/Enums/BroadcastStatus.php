<?php

namespace App\Enums;

enum BroadcastStatus: string
{
	case SCHEDULED = 'scheduled';
	case IN_PROGRESS = 'in_progress';
	case COMPLETED = 'completed';
	case FAILED = 'failed';

	public function label(): string
	{
		return match($this) {
			self::SCHEDULED => 'Planifiée',
			self::IN_PROGRESS => 'En cours',
			self::COMPLETED => 'Terminée',
			self::FAILED => 'Échouée',
		};
	}
}

