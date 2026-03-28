<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Wallet;
use Illuminate\Validation\Validator;

final readonly class HasDefaultWallet
{
    public function __construct(private ?string $excludeWalletId = null) {}

    public function __invoke(Validator $validator): void
    {
        if ($validator->safe()->boolean('is_default') === false) {
            return;
        }

        $query = Wallet::query()->where('is_default', true);

        if ($this->excludeWalletId !== null) {
            $query->where('id', '!=', $this->excludeWalletId);
        }

        if ($query->exists()) {
            $validator->errors()->add('is_default', 'A default wallet already exists.');
        }
    }
}
