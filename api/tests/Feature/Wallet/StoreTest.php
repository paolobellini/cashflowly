<?php

declare(strict_types=1);

use App\Models\Wallet;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can store a wallet', function () {
    Log::spy();

    $attributes = Wallet::factory()->make()->toArray();

    $response = $this->postJson($this->tenantUrl('/wallets'), $attributes, $this->tenantHeaders());

    $response->assertCreated();

    $this->assertDatabaseCount('wallets', 1)
        ->assertDatabaseHas('wallets', ['name' => $attributes['name']]);

    Log::shouldHaveReceived('info')->once();
});

it('fails when required fields are missing', function () {
    $response = $this->postJson($this->tenantUrl('/wallets'), [], $this->tenantHeaders());

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'type', 'currency']);
});
