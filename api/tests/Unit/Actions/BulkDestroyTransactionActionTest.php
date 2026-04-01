<?php

declare(strict_types=1);

use App\Actions\BulkDestroyTransactionAction;
use App\Models\Transaction;

beforeEach(function () {
    $this->initializeTenancy();
});

it('deletes multiple transactions', function () {
    $transactions = Transaction::factory()->count(3)->create();

    $deleted = resolve(BulkDestroyTransactionAction::class)->handle($transactions->pluck('id')->toArray());

    expect($deleted)->toBe(3)
        ->and(Transaction::query()->count())->toBe(0);
});
