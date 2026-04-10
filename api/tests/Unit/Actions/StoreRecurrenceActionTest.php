<?php

declare(strict_types=1);

use App\Actions\StoreRecurrenceAction;
use App\Models\Recurrence;

beforeEach(function () {
    $this->initializeTenancy();
});

it('creates a recurrence', function () {
    $attributes = Recurrence::factory()->make(['start_date' => '2026-04-10'])->toArray();
    $attributes['date'] = '2026-04-10';

    $recurrence = resolve(StoreRecurrenceAction::class)->handle($attributes);

    expect(Recurrence::query()->count())->toBe(1)
        ->and($recurrence->id)->toBe(Recurrence::query()->first()->id);
});

it('sets last_generated_at when transaction date matches start date', function () {
    $attributes = Recurrence::factory()->make(['start_date' => '2026-04-10'])->toArray();
    $attributes['date'] = '2026-04-10';

    $recurrence = resolve(StoreRecurrenceAction::class)->handle($attributes);

    expect($recurrence->last_generated_at->toDateString())->toBe('2026-04-10');
});

it('does not set last_generated_at when transaction date differs from start date', function () {
    $attributes = Recurrence::factory()->make(['start_date' => '2026-04-15'])->toArray();
    $attributes['date'] = '2026-04-10';

    $recurrence = resolve(StoreRecurrenceAction::class)->handle($attributes);

    expect($recurrence->last_generated_at)->toBeNull();
});
