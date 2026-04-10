<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Frequency;
use App\Enums\TransactionType;
use App\Models\Category;
use App\Models\Recurrence;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recurrence>
 */
final class RecurrenceFactory extends Factory
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
            'description' => fake()->optional()->sentence(),
            'frequency' => fake()->randomElement(Frequency::cases()),
            'start_date' => fake()->date(),
            'end_date' => null,
            'is_active' => true,
            'last_generated_at' => null,
        ];
    }

    public function income(): static
    {
        return $this->state(['type' => TransactionType::Income]);
    }

    public function expense(): static
    {
        return $this->state(['type' => TransactionType::Expense]);
    }

    public function active(): static
    {
        return $this->state(['is_active' => true]);
    }

    public function paused(): static
    {
        return $this->state(['is_active' => false]);
    }

    public function daily(): static
    {
        return $this->state(['frequency' => Frequency::Daily]);
    }

    public function weekly(): static
    {
        return $this->state(['frequency' => Frequency::Weekly]);
    }

    public function monthly(): static
    {
        return $this->state(['frequency' => Frequency::Monthly]);
    }

    public function yearly(): static
    {
        return $this->state(['frequency' => Frequency::Yearly]);
    }
}
