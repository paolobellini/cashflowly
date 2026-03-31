<?php

declare(strict_types=1);

use App\Models\Category;
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
