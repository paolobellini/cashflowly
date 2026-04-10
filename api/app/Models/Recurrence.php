<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Frequency;
use App\Enums\TransactionType;
use App\Models\Concerns\HasTransactionDetails;
use App\Observers\RecurrenceObserver;
use Database\Factories\RecurrenceFactory;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property-read string $id
 * @property-read string $wallet_id
 * @property-read string $category_id
 * @property-read TransactionType $type
 * @property-read string $amount
 * @property-read string $description
 * @property-read Frequency $frequency
 * @property-read Carbon $start_date
 * @property-read Carbon|null $end_date
 * @property-read bool $is_active
 * @property-read Carbon|null $last_generated_at
 * @property-read DateTimeImmutable|null $created_at
 * @property-read DateTimeImmutable|null $updated_at
 */
#[ObservedBy(RecurrenceObserver::class)]
final class Recurrence extends Model
{
    /** @use HasFactory<RecurrenceFactory> */
    use HasFactory;

    use HasTransactionDetails;
    use HasUuids;

    protected $keyType = 'string';

    /**
     * @return HasMany<Transaction, $this>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @param  Builder<Recurrence>  $query
     */
    protected function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function isDueOn(Carbon $date): bool
    {
        if ($this->last_generated_at === null) {
            return $date->gte($this->start_date);
        }

        return match ($this->frequency) {
            Frequency::Daily => $date->gt($this->last_generated_at),
            Frequency::Weekly => $this->last_generated_at->diffInDays($date) >= 7,
            Frequency::Monthly => $date->day === $this->start_date->day
                || ($date->isLastOfMonth() && $this->start_date->day > $date->daysInMonth),
            Frequency::Yearly => $date->month === $this->start_date->month
                && $date->day === $this->start_date->day,
        };
    }

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            ...$this->transactionDetailCasts(),
            'id' => 'string',
            'frequency' => Frequency::class,
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
            'last_generated_at' => 'date',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];
    }
}
