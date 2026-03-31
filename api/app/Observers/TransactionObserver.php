<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;

final class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        Cache::tags(['transactions'])->flush();
    }

    public function updated(Transaction $transaction): void
    {
        Cache::tags(['transactions'])->flush();
    }

    public function deleted(Transaction $transaction): void
    {
        Cache::tags(['transactions'])->flush();
    }
}
