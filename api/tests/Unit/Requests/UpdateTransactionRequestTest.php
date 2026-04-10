<?php

declare(strict_types=1);

use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    $this->initializeTenancy();
});

it('is authorized', function () {
    expect((new UpdateTransactionRequest())->authorize())->toBeTrue();
});

it('passes with valid data', function () {
    $validator = Validator::make(validTransactionData(), (new UpdateTransactionRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails when required fields are missing', function (string $field) {
    $validator = Validator::make(validTransactionData([$field => null]), (new UpdateTransactionRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has($field))->toBeTrue();
})->with(['wallet_id', 'category_id', 'type', 'amount', 'date']);
