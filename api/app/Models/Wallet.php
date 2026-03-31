<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TransactionType;
use App\Enums\WalletType;
use App\Observers\WalletObserver;
use Database\Factories\WalletFactory;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read string $id
 * @property-read string $name
 * @property-read WalletType $type
 * @property-read string $currency
 * @property-read string $initial_balance
 * @property-read bool $is_default
 * @property-read string $color
 * @property-read string|null $description
 * @property-read DateTimeImmutable|null $created_at
 * @property-read float $total_income
 * @property-read float $total_expenses
 * @property-read float $balance
 * @property-read DateTimeImmutable|null $updated_at
 */
#[ObservedBy(WalletObserver::class)]
final class Wallet extends Model
{
    /** @use HasFactory<WalletFactory> */
    use HasFactory;

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
     * @return Attribute<float, never>
     */
    public function totalIncome(): Attribute
    {
        return Attribute::get(fn (): float => (float) $this->transactions()
            ->where('type', TransactionType::Income)
            ->sum('amount'));
    }

    /**
     * @return Attribute<float, never>
     */
    public function totalExpenses(): Attribute
    {
        return Attribute::get(fn (): float => (float) $this->transactions()
            ->where('type', TransactionType::Expense)
            ->sum('amount'));
    }

    /**
     * @return Attribute<float, never>
     */
    public function balance(): Attribute
    {
        return Attribute::get(fn (): float => (float) $this->initial_balance + $this->total_income - $this->total_expenses);
    }

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'string',
            'name' => 'string',
            'type' => WalletType::class,
            'currency' => 'string',
            'initial_balance' => 'decimal:2',
            'is_default' => 'boolean',
            'color' => 'string',
            'description' => 'string',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];
    }
}
