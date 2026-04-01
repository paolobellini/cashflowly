<?php

declare(strict_types=1);

use App\Http\Requests\BulkDestroyTransactionRequest;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->initializeTenancy();
});

it('is authorized', function () {
    expect((new BulkDestroyTransactionRequest())->authorize())->toBeTrue();
});

it('passes with valid data', function () {
    $transactions = Transaction::factory()->count(3)->create();

    $validator = Validator::make(
        ['ids' => $transactions->pluck('id')->toArray()],
        (new BulkDestroyTransactionRequest())->rules(),
    );

    expect($validator->passes())->toBeTrue();
});

it('fails when ids is missing', function () {
    $validator = Validator::make([], (new BulkDestroyTransactionRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('ids'))->toBeTrue();
});

it('fails when ids exceeds max of 10', function () {
    $transactions = Transaction::factory()->count(11)->create();

    $validator = Validator::make(
        ['ids' => $transactions->pluck('id')->toArray()],
        (new BulkDestroyTransactionRequest())->rules(),
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('ids'))->toBeTrue();
});

it('fails when id does not exist', function () {
    $validator = Validator::make(
        ['ids' => [Str::uuid()->toString()]],
        (new BulkDestroyTransactionRequest())->rules(),
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('ids.0'))->toBeTrue();
});
