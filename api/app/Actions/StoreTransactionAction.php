<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Recurrence;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final readonly class StoreTransactionAction
{
    public function __construct(
        private StoreRecurrenceAction $storeRecurrenceAction,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     *
     * @throws Throwable
     */
    public function handle(array $attributes): Transaction
    {
        return DB::transaction(function () use ($attributes) {
            $recurrence = $attributes['is_recurrence']
                ? $this->storeRecurrenceAction->handle($attributes)
                : new Recurrence();

            $transaction = Transaction::query()->create([
                'wallet_id' => $attributes['wallet_id'],
                'category_id' => $attributes['category_id'],
                'type' => $attributes['type'],
                'amount' => $attributes['amount'],
                'date' => $attributes['date'],
                'description' => $attributes['description'],
                'notes' => $attributes['notes'] ?? null,
                'recurrence_id' => $recurrence->last_generated_at !== null ? $recurrence->id : null,
            ]);

            Log::info('Transaction created', ['transaction_id' => $transaction->id]);

            return $transaction;
        });
    }
}
