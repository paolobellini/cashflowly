<?php

declare(strict_types=1);

use App\Http\Requests\StoreWalletRequest;
use Illuminate\Support\Facades\Validator;

function validWalletData(array $overrides = []): array
{
    return array_merge([
        'name' => 'Personal',
        'type' => 'checking',
        'currency' => 'EUR',
        'initial_balance' => 1000.00,
        'is_default' => false,
        'color' => '#4F46E5',
        'description' => 'My main wallet',
    ], $overrides);
}

it('is authorized', function () {
    expect((new StoreWalletRequest())->authorize())->toBeTrue();
});

it('passes with valid data', function () {
    $validator = Validator::make(validWalletData(), (new StoreWalletRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails when required fields are missing', function (string $field) {
    $validator = Validator::make(validWalletData([$field => null]), (new StoreWalletRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has($field))->toBeTrue();
})->with(['name', 'type', 'currency']);
