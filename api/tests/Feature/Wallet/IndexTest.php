<?php

declare(strict_types=1);

use App\Models\Wallet;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can list all wallets', function () {
    Wallet::factory()->count(3)->create();

    $response = $this->getJson($this->tenantUrl('/wallets'), $this->tenantHeaders());

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

it('returns an empty list when no wallets exist', function () {
    $response = $this->getJson($this->tenantUrl('/wallets'), $this->tenantHeaders());

    $response->assertOk()
        ->assertJsonCount(0, 'data');
});
