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

    public function tenant(): belongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
