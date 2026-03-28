<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\WalletType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class EnumResource extends JsonResource
{
    /**
     * @return array<string, string>
     */
    public function toArray(Request $request): array
    {
        /** @var WalletType $enum */
        $enum = $this->resource;

        return [
            'value' => $enum->value,
            'label' => $enum->label(),
        ];
    }
}
