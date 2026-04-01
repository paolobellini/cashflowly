<?php

declare(strict_types=1);

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can bulk destroy transactions', function () {
    Log::spy();

    $transactions = Transaction::factory()->count(3)->create();

    $response = $this->deleteJson($this->tenantUrl('/transactions'), [
        'ids' => $transactions->pluck('id')->toArray(),
    ], $this->tenantHeaders());

    $response->assertNoContent();

    $this->assertDatabaseCount('transactions', 0);

    Log::shouldHaveReceived('info')->once();
});

it('fails when ids exceed max of 10', function () {
    $transactions = Transaction::factory()->count(11)->create();

    $response = $this->deleteJson($this->tenantUrl('/transactions'), [
        'ids' => $transactions->pluck('id')->toArray(),
    ], $this->tenantHeaders());

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['ids']);
});
