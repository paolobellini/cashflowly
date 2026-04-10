<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Frequency;
use App\Enums\TransactionType;
use App\Rules\HasSufficientBalance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            new HasSufficientBalance(),
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'wallet_id' => ['required', 'uuid', 'exists:wallets,id'],
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'type' => ['required', 'string', Rule::enum(TransactionType::class)],
            'amount' => ['required', 'numeric', 'gt:0'],
            'date' => ['required', 'date'],
            'description' => ['required', 'string', 'min:2', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_recurrence' => ['required', 'boolean'],
            'frequency' => ['nullable', Rule::when($this->boolean('is_recurrence'), ['required', Rule::enum(Frequency::class)])],
            'start_date' => ['nullable', Rule::when($this->boolean('is_recurrence'), ['required', 'date', 'after_or_equal:today'])],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}
