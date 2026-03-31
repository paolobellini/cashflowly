<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can store a transaction', function () {
    Log::spy();

    $attributes = Transaction::factory()->make()->toArray();

    $response = $this->postJson($this->tenantUrl('/transactions'), $attributes, $this->tenantHeaders());

    $response->assertCreated();

    $this->assertDatabaseCount('transactions', 1)
        ->assertDatabaseHas('transactions', ['id' => $response['data']['id']]);

    Log::shouldHaveReceived('info')->once();
});

it('fails when required fields are missing', function () {
    $response = $this->postJson($this->tenantUrl('/transactions'), [], $this->tenantHeaders());

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['wallet_id', 'category_id', 'type', 'amount', 'date', 'description']);
});
