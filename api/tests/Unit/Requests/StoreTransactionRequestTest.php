<?php

declare(strict_types=1);

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    $this->initializeTenancy();
});

function validTransactionData(array $overrides = []): array
{
    return array_merge([
        'wallet_id' => Wallet::factory()->create()->id,
        'category_id' => Category::factory()->create()->id,
        'type' => 'expense',
        'amount' => 100.50,
        'date' => '2026-03-31',
        'description' => 'Grocery shopping',
        'notes' => 'Weekly groceries',
    ], $overrides);
}

it('is authorized', function () {
    expect((new StoreTransactionRequest())->authorize())->toBeTrue();
});

it('passes with valid data', function () {
    $validator = Validator::make(validTransactionData(), (new StoreTransactionRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails when required fields are missing', function (string $field) {
    $validator = Validator::make(validTransactionData([$field => null]), (new StoreTransactionRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has($field))->toBeTrue();
})->with(['wallet_id', 'category_id', 'type', 'amount', 'date', 'description']);
