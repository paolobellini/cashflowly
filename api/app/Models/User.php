<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

/**
 * @property-read string $id
 * @property-read string|null $tenant_id
 * @property-read string $first_name
 * @property-read string $last_name
 * @property-read string $email
 * @property-read string|null $company_name
 * @property-read DateTimeImmutable|null $created_at
 * @property-read DateTimeImmutable|null $updated_at
 * @property-read Tenant $tenant
 */
final class User extends Model
{
    use CentralConnection;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
