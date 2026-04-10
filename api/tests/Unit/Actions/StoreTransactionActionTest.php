<?php

declare(strict_types=1);

use App\Actions\StoreTransactionAction;
use App\Models\Transaction;

beforeEach(function () {
    $this->initializeTenancy();
});

it('creates a transaction', function () {
    $attributes = Transaction::factory()->make()->toArray();
    $attributes['is_recurrence'] = false;

    $transaction = resolve(StoreTransactionAction::class)->handle($attributes);

    expect(Transaction::query()->count())->toBe(1)
        ->and($transaction->id)->toBe(Transaction::query()->first()->id);
});
