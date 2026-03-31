<?php

declare(strict_types=1);

namespace App\Rules;

use App\Enums\TransactionType;
use App\Exceptions\InsufficientBalanceException;
use App\Models\Wallet;
use Illuminate\Validation\Validator;

final readonly class HasSufficientBalance
{
    public function __invoke(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) {
            return;
        }

        if ($validator->getValue('type') !== TransactionType::Expense->value) {
            return;
        }

        /** @var Wallet $wallet */
        $wallet = Wallet::query()->find($validator->getValue('wallet_id'));

        if ($validator->getValue('amount') > $wallet->balance) {
            throw new InsufficientBalanceException();
        }
    }
}
