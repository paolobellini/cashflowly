<?php

declare(strict_types=1);

use App\Enums\TransactionType;

it('has the expected cases', function () {
    expect(TransactionType::cases())->toHaveCount(2)
        ->and(TransactionType::cases()[0])->toBe(TransactionType::Income)
        ->and(TransactionType::cases()[1])->toBe(TransactionType::Expense);
});

it('has a label for each case', function (TransactionType $type) {
    expect($type->label())->toBeString()->not->toBeEmpty();
})->with(TransactionType::cases());
