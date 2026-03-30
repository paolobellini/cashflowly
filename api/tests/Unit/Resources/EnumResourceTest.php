<?php

declare(strict_types=1);

use App\Enums\WalletType;
use App\Http\Resources\EnumResource;
use Illuminate\Http\Request;

it('returns value and label', function () {
    $resource = new EnumResource(WalletType::CreditCard)->toArray(new Request());

    expect($resource)->toBe([
        'value' => 'credit_card',
        'label' => 'Credit Card',
    ]);
});
