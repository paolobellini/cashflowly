<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\WalletType;
use Database\Factories\WalletFactory;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @property-read DateTimeImmutable|null $updated_at
 */
final class Wallet extends Model
{
    /** @use HasFactory<WalletFactory> */
    use HasFactory;

    use HasUuids;

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
