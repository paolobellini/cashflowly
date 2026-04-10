<?php

declare(strict_types=1);

use App\Models\Recurrence;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can destroy a recurrence', function () {
    Log::spy();

    $recurrence = Recurrence::factory()->create();

    $response = $this->deleteJson($this->tenantUrl("/recurrences/{$recurrence->id}"), [], $this->tenantHeaders());

    $response->assertNoContent();

    $this->assertDatabaseMissing('recurrences', ['id' => $recurrence->id]);

    Log::shouldHaveReceived('info')->once();
});

it('nullifies recurrence_id on related transactions', function () {
    $recurrence = Recurrence::factory()->create();
    $transaction = Transaction::factory()->for($recurrence)->create();

    $this->deleteJson($this->tenantUrl("/recurrences/{$recurrence->id}"), [], $this->tenantHeaders());

    $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'recurrence_id' => null]);
});
