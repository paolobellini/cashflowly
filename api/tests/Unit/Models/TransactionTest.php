<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Recurrence;
use App\Models\Transaction;
use App\Models\Wallet;

beforeEach(function () {
    $this->initializeTenancy();
});

it('uses the correct keys', function () {
    $transaction = Transaction::factory()->create()->fresh();

    expect(array_keys($transaction->toArray()))->toBe([
        'id',
        'wallet_id',
        'category_id',
        'type',
        'amount',
        'date',
        'description',
        'notes',
        'recurrence_id',
        'created_at',
        'updated_at',
    ]);
});

it('belongs to a wallet', function () {
    $wallet = Wallet::factory()->create();
    $transaction = Transaction::factory()->for($wallet)->create();

    expect(Wallet::query()->count())->toBe(1)
        ->and($transaction->wallet_id)->toBe($wallet->id);
});

it('belongs to a category', function () {
    $category = Category::factory()->create();
    $transaction = Transaction::factory()->for($category)->create();

    expect(Category::query()->count())->toBe(1)
        ->and($transaction->category_id)->toBe($category->id);
});

it('can filter by category', function () {
    $category = Category::factory()->create();

    Transaction::factory()->for($category)->create();
    Transaction::factory()->create();

    expect(Transaction::query()->ofCategory($category->id)->count())->toBe(1);
});

it('can filter by month', function () {
    Transaction::factory()->create(['date' => '2026-03-15']);
    Transaction::factory()->create(['date' => '2026-04-10']);

    expect(Transaction::query()->ofMonth(3)->count())->toBe(1);
});

it('can filter by year', function () {
    Transaction::factory()->create(['date' => '2026-03-15']);
    Transaction::factory()->create(['date' => '2025-03-15']);

    expect(Transaction::query()->ofYear(2026)->count())->toBe(1);
});

it('can filter by date', function () {
    Transaction::factory()->create(['date' => '2026-03-15']);
    Transaction::factory()->create(['date' => '2026-03-16']);

    expect(Transaction::query()->ofDate('2026-03-15')->count())->toBe(1);
});

it('belongs to a recurrence', function () {
    $recurrence = Recurrence::factory()->create();
    $transaction = Transaction::factory()->for($recurrence)->create();

    expect(Recurrence::query()->count())->toBe(1)
        ->and($transaction->recurrence_id)->toBe($recurrence->id);
});
