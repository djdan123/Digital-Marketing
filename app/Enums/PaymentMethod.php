<?php

namespace App\Enums;

enum PaymentMethod: string
{
	case CARD = 'card';
	case BANK_TRANSFER = 'bank_transfer';
	case PAYPAL = 'paypal';
	case CASH = 'cash';
	case OTHER = 'other';

	public function label(): string
	{
		return match($this) {
			self::CARD => 'Carte bancaire',
			self::BANK_TRANSFER => 'Virement bancaire',
			self::PAYPAL => 'PayPal',
			self::CASH => 'Espèces',
			self::OTHER => 'Autre',
		};
	}
}

