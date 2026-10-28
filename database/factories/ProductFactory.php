<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(rand(2, 4), true);

        $categories = ['Elektronik', 'Fashion', 'Kecantikan', 'Rumah Tangga', 'Makanan Kemasan', 'Olahraga', 'Aksesori', 'Otomotif'];

        return [
            'name' => ucwords($name),
            'category' => fake()->randomElement($categories),
            'slug' => Str::slug($name).'-'.Str::random(5),
            'description' => fake()->paragraph(3),
            'price' => fake()->randomFloat(2, 25000, 1500000),
            'discount_percentage' => 0,
            'stock' => fake()->numberBetween(5, 50),
            'image' => null,
        ];
    }

    /**
     * Indicate that the product has a discount.
     */
    public function discounted(int $percentage = 20): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_percentage' => $percentage,
        ]);
    }
}
