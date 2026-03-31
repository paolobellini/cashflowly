<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

final readonly class StoreTransactionAction
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Transaction
    {
        $transaction = Transaction::query()->create($attributes);

        Log::info('Transaction created', ['transaction_id' => $transaction->id]);

        return $transaction;
    }
}
