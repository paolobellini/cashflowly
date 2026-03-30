<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Wallet;
use Illuminate\Support\Facades\Cache;

final class WalletObserver
{
    public function created(Wallet $wallet): void
    {
        Cache::tags(['wallets'])->flush();
    }

    public function updated(Wallet $wallet): void
    {
        Cache::tags(['wallets'])->flush();
    }

    public function deleted(Wallet $wallet): void
    {
        Cache::tags(['wallets'])->flush();
    }
}
