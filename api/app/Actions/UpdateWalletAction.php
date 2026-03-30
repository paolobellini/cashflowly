<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Wallet;
use Illuminate\Support\Facades\Log;

final readonly class UpdateWalletAction
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Wallet $wallet, array $attributes): Wallet
    {
        $wallet->update($attributes);

        Log::info('Wallet updated', ['wallet_id' => $wallet->id]);

        return $wallet;
    }
}
