<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\ValueObjects\ApiGatewayHeaders;
use App\Models\Tenant;
use Illuminate\Support\Arr;
use Throwable;

final readonly class StoreTenantAction
{
    public function __construct(
        private AttachDomainToTenantAction $attachDomainToTenantAction,
        private StoreUserAction $storeUserAction
    ) {}

    /**
     * @param  array<string, string>  $attributes
     *
     * @throws Throwable
     */
    public function handle(ApiGatewayHeaders $headers, array $attributes): Tenant
    {
        $tenant = Tenant::query()->create();

        try {
            $this->attachDomainToTenantAction->handle($tenant, $attributes['domain']);

            /** @var array<string, mixed> $userAttributes */
            $userAttributes = Arr::except($attributes, 'domain');
            $this->storeUserAction->handle($tenant, $headers, $userAttributes);
        } catch (Throwable $e) {
            $tenant->delete();

            throw $e;
        }

        return $tenant;
    }
}
