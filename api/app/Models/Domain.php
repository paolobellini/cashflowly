<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\DomainFactory;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Models\Domain as DomainBase;

/**
 * @property-read int $id
 * @property-read string $domain
 * @property-read string $tenant_id
 * @property-read DateTimeImmutable|null $created_at
 * @property-read DateTimeImmutable|null $updated_at
 * @property-read Tenant $tenant
 */
final class Domain extends DomainBase
{
    /** @use HasFactory<DomainFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'domain' => 'string',
            'tenant_id' => 'string',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];
    }

    /**
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
