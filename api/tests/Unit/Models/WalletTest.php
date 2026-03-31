<?php

declare(strict_types=1);

use App\Models\Transaction;
use App\Models\Wallet;

beforeEach(function () {
    $this->initializeTenancy();
});

it('uses the correct keys', function () {
    $wallet = Wallet::factory()->create()->fresh();

    expect(array_keys($wallet->toArray()))->toBe([
        'id',
        'name',
        'type',
        'currency',
        'initial_balance',
        'is_default',
        'color',
        'description',
        'created_at',
        'updated_at',
    ]);
});

it('has many transactions', function () {
    $wallet = Wallet::factory()->create();

    Transaction::factory()->for($wallet)->count(3)->create();

    expect($wallet->transactions->count())->toBe(3);
});

it('computes total income from transactions', function () {
    $wallet = Wallet::factory()->create();

    Transaction::factory()->for($wallet)->income()->create(['amount' => 500]);
    Transaction::factory()->for($wallet)->income()->create(['amount' => 300]);
    Transaction::factory()->for($wallet)->expense()->create(['amount' => 100]);

    expect($wallet->total_income)->toBe(800.0);
});

it('computes total expenses from transactions', function () {
    $wallet = Wallet::factory()->create();

    Transaction::factory()->for($wallet)->expense()->create(['amount' => 200]);
    Transaction::factory()->for($wallet)->expense()->create(['amount' => 150]);
    Transaction::factory()->for($wallet)->income()->create(['amount' => 500]);

    expect($wallet->total_expenses)->toBe(350.0);
});

it('computes balance from initial balance and transactions', function () {
    $wallet = Wallet::factory()->create(['initial_balance' => 1000]);

    Transaction::factory()->for($wallet)->income()->create(['amount' => 500]);
    Transaction::factory()->for($wallet)->expense()->create(['amount' => 200]);

    expect($wallet->balance)->toBe(1300.0);
});
