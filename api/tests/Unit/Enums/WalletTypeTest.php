<?php

declare(strict_types=1);

use App\Enums\WalletType;

it('has the expected cases', function () {
    expect(WalletType::cases())->toHaveCount(5)
        ->and(WalletType::cases()[0])->toBe(WalletType::Checking)
        ->and(WalletType::cases()[1])->toBe(WalletType::Savings)
        ->and(WalletType::cases()[2])->toBe(WalletType::CreditCard)
        ->and(WalletType::cases()[3])->toBe(WalletType::Cash)
        ->and(WalletType::cases()[4])->toBe(WalletType::Investment);
});

it('has a label for each case', function (WalletType $type) {
    expect($type->label())->toBeString()->not->toBeEmpty();
})->with(WalletType::cases());
