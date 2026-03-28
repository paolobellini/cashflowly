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

    expect(Wallet::query()->count())->toBe(1)
        ->and(Wallet::query()->where('name', $attributes['name'])->exists())->toBeTrue();

    Log::shouldHaveReceived('info')->once();
});

it('fails when required fields are missing', function () {
    $response = $this->postJson($this->tenantUrl('/wallets'), [], $this->tenantHeaders());

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'type', 'currency']);
});
