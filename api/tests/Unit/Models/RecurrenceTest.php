<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Recurrence;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->initializeTenancy();
});

it('uses the correct keys', function () {
    $recurrence = Recurrence::factory()->create()->fresh();

    expect(array_keys($recurrence->toArray()))->toBe([
        'id',
        'wallet_id',
        'category_id',
        'type',
        'amount',
        'description',
        'frequency',
        'start_date',
        'end_date',
        'is_active',
        'last_generated_at',
        'created_at',
        'updated_at',
    ]);
});

it('belongs to a wallet', function () {
    $wallet = Wallet::factory()->create();
    $recurrence = Recurrence::factory()->for($wallet)->create();

    expect(Wallet::query()->count())->toBe(1)
        ->and($recurrence->wallet_id)->toBe($wallet->id);
});

it('belongs to a category', function () {
    $category = Category::factory()->create();
    $recurrence = Recurrence::factory()->for($category)->create();

    expect(Category::query()->count())->toBe(1)
        ->and($recurrence->category_id)->toBe($category->id);
});

it('has many transactions', function () {
    $recurrence = Recurrence::factory()->create();

    Transaction::factory()->for($recurrence)->count(3)->create();
    Transaction::factory()->create();

    expect($recurrence->transactions)->toHaveCount(3);
});

it('can filter active recurrences', function () {
    Recurrence::factory()->active()->create();
    Recurrence::factory()->paused()->create();

    expect(Recurrence::query()->active()->count())->toBe(1);
});

it('is due when never generated and date is on or after start date', function () {
    $recurrence = Recurrence::factory()->daily()->create([
        'start_date' => '2026-04-01',
        'last_generated_at' => null,
    ]);

    expect($recurrence->isDueOn(Carbon::parse('2026-04-01')))->toBeTrue()
        ->and($recurrence->isDueOn(Carbon::parse('2026-04-10')))->toBeTrue()
        ->and($recurrence->isDueOn(Carbon::parse('2026-03-31')))->toBeFalse();
});

it('is due daily when date is after last generated', function () {
    $recurrence = Recurrence::factory()->daily()->create([
        'start_date' => '2026-04-01',
        'last_generated_at' => '2026-04-05',
    ]);

    expect($recurrence->isDueOn(Carbon::parse('2026-04-06')))->toBeTrue()
        ->and($recurrence->isDueOn(Carbon::parse('2026-04-05')))->toBeFalse();
});

it('is due weekly when at least 7 days have passed', function () {
    $recurrence = Recurrence::factory()->weekly()->create([
        'start_date' => '2026-04-01',
        'last_generated_at' => '2026-04-01',
    ]);

    expect($recurrence->isDueOn(Carbon::parse('2026-04-08')))->toBeTrue()
        ->and($recurrence->isDueOn(Carbon::parse('2026-04-07')))->toBeFalse();
});

it('is due monthly on the same day or last day of shorter months', function () {
    $recurrence = Recurrence::factory()->monthly()->create([
        'start_date' => '2026-01-31',
        'last_generated_at' => '2026-01-31',
    ]);

    expect($recurrence->isDueOn(Carbon::parse('2026-03-31')))->toBeTrue()
        ->and($recurrence->isDueOn(Carbon::parse('2026-02-28')))->toBeTrue()
        ->and($recurrence->isDueOn(Carbon::parse('2026-02-27')))->toBeFalse();
});

it('is due yearly on the same month and day', function () {
    $recurrence = Recurrence::factory()->yearly()->create([
        'start_date' => '2026-04-10',
        'last_generated_at' => '2026-04-10',
    ]);

    expect($recurrence->isDueOn(Carbon::parse('2027-04-10')))->toBeTrue()
        ->and($recurrence->isDueOn(Carbon::parse('2027-04-11')))->toBeFalse()
        ->and($recurrence->isDueOn(Carbon::parse('2027-03-10')))->toBeFalse();
});
