<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TransactionType;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
final class TransactionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wallet_id' => Wallet::factory(),
            'category_id' => Category::factory(),
            'type' => fake()->randomElement(TransactionType::cases()),
            'amount' => fake()->randomFloat(2, 1, 5000),
            'date' => fake()->date(),
            'description' => fake()->sentence(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
