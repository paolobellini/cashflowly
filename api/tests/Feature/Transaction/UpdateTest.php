<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can update a transaction', function () {
    Log::spy();

    $wallet = Wallet::factory()->create(['initial_balance' => 5000]);
    $category = Category::factory()->create();
    $transaction = Transaction::factory()->expense()->for($wallet)->for($category)->create(['description' => 'Old Description']);

    $attributes = Transaction::factory()->expense()->for($wallet)->for($category)->make([
        'amount' => 100,
        'description' => 'New Description',
    ])->toArray();

    $response = $this->putJson($this->tenantUrl("/transactions/{$transaction->id}"), $attributes, $this->tenantHeaders());

    $response->assertOk();

    $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'description' => 'New Description']);

    Log::shouldHaveReceived('info')->once();
});

it('fails when required fields are missing', function () {
    $transaction = Transaction::factory()->create();

    $response = $this->putJson($this->tenantUrl("/transactions/{$transaction->id}"), [], $this->tenantHeaders());

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['wallet_id', 'category_id', 'type', 'amount', 'date', 'description']);
});
