<?php

declare(strict_types=1);

use App\Actions\UpdateWalletAction;
use App\Models\Wallet;

beforeEach(function () {
    $this->initializeTenancy();
});

it('updates a wallet', function () {
    $wallet = Wallet::factory()->create(['name' => 'Old Name']);

    resolve(UpdateWalletAction::class)->handle($wallet, ['name' => 'New Name']);

    expect($wallet->fresh()->name)->toBe('New Name');
});
