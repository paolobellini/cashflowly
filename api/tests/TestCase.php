<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    protected bool $tenancy = false;

    protected Tenant $tenant;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');

        if ($this->tenancy) {
            $this->initializeTenancy();
        }
    }

    protected function tearDown(): void
    {
        if ($this->tenancy) {
            tenancy()->end();
            $this->tenant->delete();
        }

        parent::tearDown();
    }

    protected function initializeTenancy(): void
    {
        $this->tenant = Tenant::query()->create();
        $this->tenant->domains()->create(['domain' => 'test.localhost']);
        $this->user = User::factory()->create(['tenant_id' => $this->tenant->id]);

        tenancy()->initialize($this->tenant);
    }

    protected function tenantUrl(string $path): string
    {
        return 'http://test.localhost'.$path;
    }

    /**
     * @return array<string, string>
     */
    protected function tenantHeaders(): array
    {
        return [
            'X-User-Id' => (string) $this->user->id,
            'X-Tenant-Id' => $this->tenant->id,
        ];
    }
}
