<?php

declare(strict_types=1);

namespace App\Actions;

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
            $transactionData = collect($attributes)
                ->except(['is_recurrence', 'frequency', 'start_date', 'end_date'])
                ->all();

            if ($attributes['is_recurrence']) {
                $recurrence = $this->storeRecurrenceAction->handle($attributes);

                if ($recurrence->last_generated_at !== null) {
                    $transactionData['recurrence_id'] = $recurrence->id;
                }
            }

            $transaction = Transaction::query()->create($transactionData);

            Log::info('Transaction created', ['transaction_id' => $transaction->id]);

            return $transaction;
        });
    }
}
