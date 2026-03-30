<?php

declare(strict_types=1);

use App\Models\Wallet;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can update a wallet', function () {
    Log::spy();

    $wallet = Wallet::factory()->create(['name' => 'Old Name']);

    $response = $this->putJson($this->tenantUrl("/wallets/{$wallet->id}"), [
        'name' => 'New Name',
        'type' => 'savings',
        'initial_balance' => 500.00,
        'is_default' => false,
        'color' => '#FF5733',
        'description' => 'Updated wallet',
    ], $this->tenantHeaders());

    $response->assertOk();

    $this->assertDatabaseHas('wallets', ['id' => $wallet->id, 'name' => 'New Name']);

    Log::shouldHaveReceived('info')->once();
});

it('fails when required fields are missing', function () {
    $wallet = Wallet::factory()->create();

    $response = $this->putJson($this->tenantUrl("/wallets/{$wallet->id}"), [], $this->tenantHeaders());

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'type']);
});
