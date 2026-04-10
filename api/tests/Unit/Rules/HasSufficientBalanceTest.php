<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;

beforeEach(function () {
    $this->initializeTenancy();
});

it('passes when wallet has sufficient balance', function () {
    $wallet = Wallet::factory()->create(['initial_balance' => 1000]);
    $category = Category::factory()->create();

    $attributes = Transaction::factory()->expense()->for($wallet)->for($category)->make(['amount' => 500])->toArray();
    $attributes['is_recurrence'] = false;

    $response = $this->postJson($this->tenantUrl('/transactions'), $attributes, $this->tenantHeaders());

    $response->assertCreated();
});

it('fails when wallet has insufficient balance', function () {
    $wallet = Wallet::factory()->create(['initial_balance' => 100]);
    $category = Category::factory()->create();

    $attributes = Transaction::factory()->expense()->for($wallet)->for($category)->make(['amount' => 500])->toArray();
    $attributes['is_recurrence'] = false;

    $response = $this->postJson($this->tenantUrl('/transactions'), $attributes, $this->tenantHeaders());

    $response->assertUnprocessable();
});

it('skips balance check for income transactions', function () {
    $wallet = Wallet::factory()->create(['initial_balance' => 0]);
    $category = Category::factory()->create();

    $attributes = Transaction::factory()->income()->for($wallet)->for($category)->make(['amount' => 5000])->toArray();
    $attributes['is_recurrence'] = false;

    $response = $this->postJson($this->tenantUrl('/transactions'), $attributes, $this->tenantHeaders());

    $response->assertCreated();
});
