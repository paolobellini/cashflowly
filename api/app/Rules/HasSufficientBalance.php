<?php

declare(strict_types=1);

namespace App\Rules;

use App\Enums\TransactionType;
use App\Exceptions\InsufficientBalanceException;
use App\Models\Wallet;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

final class HasSufficientBalance implements DataAwareRule, ValidationRule
{
    /** @var array<string, mixed> */
    private array $data = [];

    /**
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->data['type'] !== TransactionType::Expense->value) {
            return;
        }

        /** @var Wallet $wallet */
        $wallet = Wallet::query()->find($this->data['wallet_id']);

        if (is_numeric($value) && (float) $value > $wallet->balance) {
            throw new InsufficientBalanceException();
        }
    }
}
