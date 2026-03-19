<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Tenant;

final readonly class AttachDomainToTenantAction
{
    public function handle(Tenant $tenant, string $domain): void
    {
        $tenant->domain()->create([
            'domain' => $domain,
        ]);
    }
}
