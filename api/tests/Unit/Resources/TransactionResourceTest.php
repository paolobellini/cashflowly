<?php

declare(strict_types=1);

use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;

beforeEach(function () {
    $this->initializeTenancy();
});

it('returns the correct structure', function () {
    $transaction = Transaction::factory()->create();

    $resource = new TransactionResource($transaction)->toArray(new Request());

    expect($resource)
        ->toHaveKeys(['id', 'wallet_id', 'category_id', 'type', 'amount', 'date', 'description', 'notes'])
        ->not->toHaveKey('created_at')
        ->not->toHaveKey('updated_at');
});
