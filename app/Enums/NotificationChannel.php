<?php

namespace App\Enums;

enum NotificationChannel: string
{
	case EMAIL = 'email';
	case SMS = 'sms';
	case PUSH = 'push';

	public function label(): string
	{
		return match($this) {
			self::EMAIL => 'Email',
			self::SMS => 'SMS',
			self::PUSH => 'Notification push',
		};
	}
}
