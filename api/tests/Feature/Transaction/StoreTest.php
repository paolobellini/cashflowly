<?php

declare(strict_types=1);

use App\Models\Recurrence;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can store a transaction', function () {
    Log::spy();

    $attributes = Transaction::factory()->income()->make()->toArray();
    $attributes['is_recurrence'] = false;

    $response = $this->postJson($this->tenantUrl('/transactions'), $attributes, $this->tenantHeaders());

    $response->assertCreated();

    $this->assertDatabaseCount('transactions', 1)
        ->assertDatabaseCount('recurrences', 0)
        ->assertDatabaseHas('transactions', ['id' => $response['data']['id']]);

    Log::shouldHaveReceived('info')->once();
});

it('can store a transaction with a recurrence', function () {
    Log::spy();

    $today = today()->toDateString();
    $attributes = Transaction::factory()->income()->make(['date' => $today])->toArray();
    $attributes['is_recurrence'] = true;
    $attributes['frequency'] = 'monthly';
    $attributes['start_date'] = $today;

    $response = $this->postJson($this->tenantUrl('/transactions'), $attributes, $this->tenantHeaders());

    $response->assertCreated();

    $this->assertDatabaseCount('transactions', 1)
        ->assertDatabaseCount('recurrences', 1)
        ->assertDatabaseHas('transactions', ['recurrence_id' => Recurrence::query()->first()->id])
        ->assertDatabaseHas('recurrences', ['last_generated_at' => $today]);

    Log::shouldHaveReceived('info')->twice();
});

it('does not link transaction to recurrence when dates differ', function () {
    Log::spy();

    $tomorrow = today()->addDay()->toDateString();
    $attributes = Transaction::factory()->income()->make(['date' => today()->toDateString()])->toArray();
    $attributes['is_recurrence'] = true;
    $attributes['frequency'] = 'monthly';
    $attributes['start_date'] = $tomorrow;

    $response = $this->postJson($this->tenantUrl('/transactions'), $attributes, $this->tenantHeaders());

    $response->assertCreated();

    $this->assertDatabaseCount('transactions', 1)
        ->assertDatabaseCount('recurrences', 1)
        ->assertDatabaseHas('transactions', ['recurrence_id' => null])
        ->assertDatabaseHas('recurrences', ['last_generated_at' => null]);

    Log::shouldHaveReceived('info')->twice();
});

it('fails when required fields are missing', function () {
    $response = $this->postJson($this->tenantUrl('/transactions'), [], $this->tenantHeaders());

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['wallet_id', 'category_id', 'type', 'amount', 'date', 'description', 'is_recurrence']);
});
