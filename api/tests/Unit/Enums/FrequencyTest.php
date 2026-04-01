<?php

declare(strict_types=1);

use App\Enums\Frequency;

it('has the expected cases', function () {
    expect(Frequency::cases())->toHaveCount(4)
        ->and(Frequency::cases()[0])->toBe(Frequency::Daily)
        ->and(Frequency::cases()[1])->toBe(Frequency::Weekly)
        ->and(Frequency::cases()[2])->toBe(Frequency::Monthly)
        ->and(Frequency::cases()[3])->toBe(Frequency::Yearly);
});

it('has a label for each case', function (Frequency $frequency) {
    expect($frequency->label())->toBeString()->not->toBeEmpty();
})->with(Frequency::cases());
