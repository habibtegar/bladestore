<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'label' => fake()->randomElement(['Rumah', 'Kantor', 'Apartemen', 'Kos']),
            'recipient_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'full_address' => fake()->streetAddress().', RT 01/RW 02, Kel. Sukamaju',
            'city' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'is_primary' => false,
        ];
    }

    /**
     * Indicate that the address is primary.
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
        ]);
    }
}
