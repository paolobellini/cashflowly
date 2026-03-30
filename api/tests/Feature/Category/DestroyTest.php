<?php

declare(strict_types=1);

use App\Models\Category;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can destroy a category', function () {
    Log::spy();

    $category = Category::factory()->create();

    $response = $this->deleteJson($this->tenantUrl("/categories/{$category->id}"), [], $this->tenantHeaders());

    $response->assertNoContent();

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);

    Log::shouldHaveReceived('info')->once();
});
