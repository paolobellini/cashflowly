<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\WalletType;
use App\Rules\HasDefaultWallet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateWalletRequest extends FormRequest
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
        /** @var Wallet $wallet */
        $wallet = $this->route('wallet');

        return [
            new HasDefaultWallet($wallet->id),
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'type' => ['required', 'string', Rule::enum(WalletType::class)],
            'initial_balance' => ['nullable', 'numeric', 'min:0'],
            'is_default' => ['nullable', 'boolean'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
