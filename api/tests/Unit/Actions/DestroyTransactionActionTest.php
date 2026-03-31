<?php

declare(strict_types=1);

use App\Actions\DestroyTransactionAction;
use App\Models\Transaction;

beforeEach(function () {
    $this->initializeTenancy();
});

it('deletes a transaction', function () {
    $transaction = Transaction::factory()->create();

    resolve(DestroyTransactionAction::class)->handle($transaction);

    expect(Transaction::query()->count())->toBe(0);
});
