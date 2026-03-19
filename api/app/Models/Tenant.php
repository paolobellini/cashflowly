<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

final class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /* @use HasFactory<TenantFactory> * */
    use HasFactory;

    /**
     * @return list<string>
     */
    public function casts(): array
    {
        return [
            'id' => 'string',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
            'data' => 'array',
        ];
    }

    public function domain(): HasOne
    {
        return $this->hasOne(Domain::class);
    }
}
