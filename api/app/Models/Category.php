<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CategoryType;
use Database\Factories\CategoryFactory;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read string $id
 * @property-read string $name
 * @property-read CategoryType $type
 * @property-read string $icon
 * @property-read string $color
 * @property-read DateTimeImmutable|null $created_at
 * @property-read DateTimeImmutable|null $updated_at
 */
final class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
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
     * @param  Builder<Category>  $query
     */
    protected function scopeOfType(Builder $query, CategoryType $type): void
    {
        $query->where('type', $type);
    }

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'string',
            'name' => 'string',
            'type' => CategoryType::class,
            'icon' => 'string',
            'color' => 'string',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];
    }
}
