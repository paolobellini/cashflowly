<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Wallet;
use Illuminate\Support\Facades\Log;

final readonly class DestroyWalletAction
{
    public function handle(Wallet $wallet): void
    {
        $wallet->delete();

        Log::info('Wallet deleted', ['wallet_id' => $wallet->id]);
    }
}
