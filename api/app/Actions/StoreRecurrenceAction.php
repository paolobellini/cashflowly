<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Recurrence;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

final readonly class StoreRecurrenceAction
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Recurrence
    {
        /** @var string $transactionDate */
        $transactionDate = $attributes['date'];
        /** @var string $startDate */
        $startDate = $attributes['start_date'];

        $recurrence = Recurrence::query()->create([
            'wallet_id' => $attributes['wallet_id'],
            'category_id' => $attributes['category_id'],
            'type' => $attributes['type'],
            'amount' => $attributes['amount'],
            'description' => $attributes['description'],
            'frequency' => $attributes['frequency'],
            'start_date' => $attributes['start_date'],
            'end_date' => $attributes['end_date'] ?? null,
            'last_generated_at' => $this->lastGeneratedAt(Carbon::parse($transactionDate), Carbon::parse($startDate)),
        ]);

        Log::info('Recurrence created', ['recurrence_id' => $recurrence->id]);

        return $recurrence;
    }

    private function lastGeneratedAt(Carbon $transactionDate, Carbon $startDate): ?Carbon
    {
        return $transactionDate->equalTo($startDate) ? $transactionDate : null;
    }
}
