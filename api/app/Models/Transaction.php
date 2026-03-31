<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TransactionType;
use Database\Factories\TransactionFactory;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property-read string $wallet_id
 * @property-read string $category_id
 * @property-read TransactionType $type
 * @property-read string $amount
 * @property-read string $date
 * @property-read string $description
 * @property-read string|null $notes
 * @property-read DateTimeImmutable|null $created_at
 * @property-read DateTimeImmutable|null $updated_at
 */
final class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory;

    use HasUuids;

    protected $keyType = 'string';

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
     * @param  Builder<Transaction>  $query
     */
    protected function scopeOfMonth(Builder $query, int $month): void
    {
        $query->whereMonth('date', $month);
    }

    /**
     * @param  Builder<Transaction>  $query
     */
    protected function scopeOfYear(Builder $query, int $year): void
    {
        $query->whereYear('date', $year);
    }

    /**
     * @param  Builder<Transaction>  $query
     */
    protected function scopeOfDate(Builder $query, string $date): void
    {
        $query->whereDate('date', $date);
    }

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'string',
            'wallet_id' => 'string',
            'category_id' => 'string',
            'type' => TransactionType::class,
            'amount' => 'decimal:2',
            'date' => 'date',
            'description' => 'string',
            'notes' => 'string',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];
    }
}
