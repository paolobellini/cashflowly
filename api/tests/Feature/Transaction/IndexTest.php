<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Transaction;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can list all transactions paginated', function () {
    Transaction::factory()->count(30)->create();

    $response = $this->getJson($this->tenantUrl('/transactions'), $this->tenantHeaders());

    $response->assertOk()
        ->assertJsonCount(25, 'data')
        ->assertJsonPath('meta.per_page', 25);
});

it('can filter transactions by category', function () {
    $category = Category::factory()->create();

    Transaction::factory()->for($category)->count(2)->create();
    Transaction::factory()->count(3)->create();

    $response = $this->getJson($this->tenantUrl("/transactions?category_id={$category->id}"), $this->tenantHeaders());

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

it('can filter transactions by month and year', function () {
    Transaction::factory()->count(2)->create(['date' => '2026-03-15']);
    Transaction::factory()->create(['date' => '2026-04-10']);

    $response = $this->getJson($this->tenantUrl('/transactions?month=3&year=2026'), $this->tenantHeaders());

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

it('returns an empty list when no transactions exist', function () {
    $response = $this->getJson($this->tenantUrl('/transactions'), $this->tenantHeaders());

    $response->assertOk()
        ->assertJsonCount(0, 'data');
});
