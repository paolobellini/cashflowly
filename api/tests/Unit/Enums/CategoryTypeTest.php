<?php

declare(strict_types=1);

use App\Enums\CategoryType;

it('has the expected cases', function () {
    expect(CategoryType::cases())->toHaveCount(3)
        ->and(CategoryType::cases()[0])->toBe(CategoryType::Income)
        ->and(CategoryType::cases()[1])->toBe(CategoryType::Expense)
        ->and(CategoryType::cases()[2])->toBe(CategoryType::Both);
});

it('has a label for each case', function (CategoryType $type) {
    expect($type->label())->toBeString()->not->toBeEmpty();
})->with(CategoryType::cases());
