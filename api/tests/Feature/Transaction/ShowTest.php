<?php

declare(strict_types=1);

use App\Models\Transaction;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can show a transaction', function () {
    $transaction = Transaction::factory()->create();

    $response = $this->getJson($this->tenantUrl("/transactions/{$transaction->id}"), $this->tenantHeaders());

    $response->assertOk()
        ->assertJsonPath('data.id', $transaction->id);
});
