<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TransactionType;
use App\Models\Concerns\HasTransactionDetails;
use App\Observers\TransactionObserver;
use Database\Factories\TransactionFactory;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
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
 * @property-read string|null $description
 * @property-read string|null $recurrence_id
 * @property-read DateTimeImmutable|null $created_at
 * @property-read DateTimeImmutable|null $updated_at
 */
#[ObservedBy(TransactionObserver::class)]
final class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory;

    use HasTransactionDetails;
    use HasUuids;

    protected $keyType = 'string';

    /**
     * @return BelongsTo<Recurrence, $this>
     */
    public function recurrence(): BelongsTo
    {
        return $this->belongsTo(Recurrence::class);
    }

    /**
     * @param  Builder<Transaction>  $query
     */
    protected function scopeOfCategory(Builder $query, string $categoryId): void
    {
        $query->whereRelation('category', 'id', $categoryId);
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
            ...$this->transactionDetailCasts(),
            'id' => 'string',
            'date' => 'date',
            'recurrence_id' => 'string',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];
    }
}
