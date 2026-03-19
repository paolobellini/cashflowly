<?php

declare(strict_types=1);

namespace App\Http\ValueObjects;

use Illuminate\Http\Request;

final readonly class ApiGatewayHeaders
{
    public function __construct(
        public string $userId,
        public string $userEmail,
        public ?string $tenantId = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            userId: (string) $request->headers->get('X-User-Id', ''),
            userEmail: (string) $request->headers->get('X-User-Email', ''),
            tenantId: $request->headers->get('X-Tenant-Id'),
        );
    }
}
