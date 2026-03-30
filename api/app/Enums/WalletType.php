<?php

declare(strict_types=1);

namespace App\Enums;

enum WalletType: string
{
    case Checking = 'checking';
    case Savings = 'savings';
    case CreditCard = 'credit_card';
    case Cash = 'cash';
    case Investment = 'investment';

    public function label(): string
    {
        return match ($this) {
            self::Checking => 'Checking',
            self::Savings => 'Savings',
            self::CreditCard => 'Credit Card',
            self::Cash => 'Cash',
            self::Investment => 'Investment',
        };
    }
}
