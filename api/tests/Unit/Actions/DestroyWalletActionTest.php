<?php

declare(strict_types=1);

use App\Actions\DestroyWalletAction;
use App\Models\Wallet;

beforeEach(function () {
    $this->initializeTenancy();
});

it('deletes a wallet', function () {
    $wallet = Wallet::factory()->create();

    resolve(DestroyWalletAction::class)->handle($wallet);

    expect(Wallet::query()->count())->toBe(0);
});
