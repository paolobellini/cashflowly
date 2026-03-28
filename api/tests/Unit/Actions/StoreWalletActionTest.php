<?php

declare(strict_types=1);

use App\Actions\StoreWalletAction;
use App\Models\Wallet;

beforeEach(function () {
    $this->initializeTenancy();
});

it('creates a wallet', function () {
    $attributes = Wallet::factory()->make()->toArray();

    $wallet = resolve(StoreWalletAction::class)->handle($attributes);

    expect(Wallet::query()->count())->toBe(1)
        ->and($wallet->id)->toBe(Wallet::query()->first()->id);
});
