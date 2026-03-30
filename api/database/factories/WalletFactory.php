<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\WalletType;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Wallet>
 */
final class WalletFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Personal', 'Work', 'Savings', 'Travel', 'Emergency']),
            'type' => fake()->randomElement(WalletType::cases()),
            'currency' => 'EUR',
            'initial_balance' => fake()->randomFloat(2, 0, 10000),
            'is_default' => false,
            'color' => fake()->hexColor(),
            'description' => fake()->optional()->sentence(),
        ];
    }

    public function default(): static
    {
        return $this->state(['is_default' => true]);
    }
}
