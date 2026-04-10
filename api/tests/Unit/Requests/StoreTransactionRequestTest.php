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
        'is_recurrence' => false,
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
})->with(['wallet_id', 'category_id', 'type', 'amount', 'date', 'description', 'is_recurrence']);

it('fails when recurrence fields are missing while is_recurrence is true', function (string $field) {
    $data = validTransactionData([
        'is_recurrence' => true,
        'frequency' => 'monthly',
        'start_date' => today()->toDateString(),
        $field => null,
    ]);

    $request = new StoreTransactionRequest();
    $request->merge($data);

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has($field))->toBeTrue();
})->with(['frequency', 'start_date']);

it('passes without recurrence fields when is_recurrence is false', function () {
    $validator = Validator::make(validTransactionData(), (new StoreTransactionRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('passes with valid recurrence data', function () {
    $data = validTransactionData([
        'is_recurrence' => true,
        'frequency' => 'monthly',
        'start_date' => today()->toDateString(),
        'end_date' => today()->addYear()->toDateString(),
    ]);

    $request = new StoreTransactionRequest();
    $request->merge($data);

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});
