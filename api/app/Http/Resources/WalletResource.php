<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class WalletResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Wallet $wallet */
        $wallet = $this->resource;

        return [
            'id' => $wallet->id,
            'name' => $wallet->name,
            'type' => new EnumResource($wallet->type),
            'currency' => $wallet->currency,
            'initial_balance' => $wallet->initial_balance,
            'is_default' => $wallet->is_default,
            'color' => $wallet->color,
            'description' => $wallet->description,
        ];
    }
}
