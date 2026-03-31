<?php

declare(strict_types=1);

use App\Actions\UpdateTransactionAction;
use App\Models\Transaction;

beforeEach(function () {
    $this->initializeTenancy();
});

it('updates a transaction', function () {
    $transaction = Transaction::factory()->create(['description' => 'Old Description']);

    resolve(UpdateTransactionAction::class)->handle($transaction, ['description' => 'New Description']);

    expect($transaction->fresh()->description)->toBe('New Description');
});
