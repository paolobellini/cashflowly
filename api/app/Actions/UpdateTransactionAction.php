<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

final readonly class UpdateTransactionAction
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Transaction $transaction, array $attributes): Transaction
    {
        $transaction->update($attributes);

        Log::info('Transaction updated', ['transaction_id' => $transaction->id]);

        return $transaction;
    }
}
