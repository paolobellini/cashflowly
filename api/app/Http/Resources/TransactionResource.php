<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class TransactionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Transaction $transaction */
        $transaction = $this->resource;

        return [
            'id' => $transaction->id,
            'wallet_id' => $transaction->wallet_id,
            'category_id' => $transaction->category_id,
            'type' => new EnumResource($transaction->type),
            'amount' => $transaction->amount,
            'date' => $transaction->date,
            'description' => $transaction->description,
            'notes' => $transaction->notes,
        ];
    }
}
