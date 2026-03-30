<?php

declare(strict_types=1);

use App\Models\Wallet;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can destroy a wallet', function () {
    Log::spy();

    $wallet = Wallet::factory()->create();

    $response = $this->deleteJson($this->tenantUrl("/wallets/{$wallet->id}"), [], $this->tenantHeaders());

    $response->assertNoContent();

    $this->assertDatabaseMissing('wallets', ['id' => $wallet->id]);

    Log::shouldHaveReceived('info')->once();
});
