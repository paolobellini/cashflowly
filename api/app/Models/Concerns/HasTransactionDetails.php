<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\TransactionType;
use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasTransactionDetails
{
    /**
     * @return BelongsTo<Wallet, $this>
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return array<string, string>
     */
    public function transactionDetailCasts(): array
    {
        return [
            'wallet_id' => 'string',
            'category_id' => 'string',
            'type' => TransactionType::class,
            'amount' => 'decimal:2',
            'description' => 'string',
        ];
    }
}
