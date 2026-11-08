<?php

namespace Database\Factories;

use App\Models\NightlyInventory;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NightlyInventory>
 */
class NightlyInventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_type_id' => RoomType::factory(),
            'date' => fake()->dateTimeBetween('+1 day', '+90 days')->format('Y-m-d'),
            'rooms_left' => fake()->numberBetween(1, 10),
            'price' => fake()->numberBetween(8000, 40000),
        ];
    }
}
