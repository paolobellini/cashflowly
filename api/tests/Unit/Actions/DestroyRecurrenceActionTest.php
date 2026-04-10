<?php

declare(strict_types=1);

use App\Actions\DestroyRecurrenceAction;
use App\Models\Recurrence;

beforeEach(function () {
    $this->initializeTenancy();
});

it('deletes a recurrence', function () {
    $recurrence = Recurrence::factory()->create();

    resolve(DestroyRecurrenceAction::class)->handle($recurrence);

    expect(Recurrence::query()->count())->toBe(0);
});
