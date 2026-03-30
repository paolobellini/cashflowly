<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CategoryType;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
final class CategoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Food & Dining', 'Transport', 'Salary', 'Rent', 'Entertainment', 'Shopping', 'Utilities', 'Health']),
            'type' => fake()->randomElement(CategoryType::cases()),
            'icon' => fake()->randomElement(['utensils', 'car', 'briefcase', 'home', 'film', 'shopping-bag', 'zap', 'heart']),
            'color' => fake()->hexColor(),
        ];
    }
}
