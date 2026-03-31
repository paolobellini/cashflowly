<?php

declare(strict_types=1);

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can destroy a transaction', function () {
    Log::spy();

    $transaction = Transaction::factory()->create();

    $response = $this->deleteJson($this->tenantUrl("/transactions/{$transaction->id}"), [], $this->tenantHeaders());

    $response->assertNoContent();

    $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);

    Log::shouldHaveReceived('info')->once();
});
