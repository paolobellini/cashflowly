<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Models\Domain as DomainBase;

final class Domain extends DomainBase
{
    /* @uses HasFactory<DomainFactory> * */
    use HasFactory;

    /***
     * @return list<string>
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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
