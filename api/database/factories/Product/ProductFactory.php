<?php

declare(strict_types=1);

namespace Database\Factories\Product;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $active = fake()->boolean;

        return [
            'article' => fake()->firstName() . fake()->randomDigit(),
            'name' => fake()->name(),
            'status' => $active ? Product::STATUS_AVAILABLE : Product::STATUS_UNAVAILABLE,
            'data' => fake()->words(),
        ];
    }
}
