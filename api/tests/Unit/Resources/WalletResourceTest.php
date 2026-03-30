<?php

declare(strict_types=1);

use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use Illuminate\Http\Request;

beforeEach(function () {
    $this->initializeTenancy();
});

it('returns the correct structure', function () {
    $wallet = Wallet::factory()->create();

    $resource = new WalletResource($wallet)->toArray(new Request());

    expect($resource)
        ->toHaveKeys(['id', 'name', 'type', 'currency', 'initial_balance', 'is_default', 'color', 'description'])
        ->not->toHaveKey('created_at')
        ->not->toHaveKey('updated_at');
});
