<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

final readonly class DestroyTransactionAction
{
    public function handle(Transaction $transaction): void
    {
        $transaction->delete();

        Log::info('Transaction deleted', ['transaction_id' => $transaction->id]);
    }
}
