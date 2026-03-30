<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Wallet;
use Illuminate\Support\Facades\Log;

final readonly class StoreWalletAction
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Wallet
    {
        $wallet = Wallet::query()->create($attributes);

        Log::info('Wallet created', ['wallet_id' => $wallet->id]);

        return $wallet;
    }
}
