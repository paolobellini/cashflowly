<?php

declare(strict_types=1);

use App\Models\Wallet;

beforeEach(function () {
    $this->tenancy = true;
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
