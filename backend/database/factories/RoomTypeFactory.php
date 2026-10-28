<?php

namespace Database\Factories;

use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoomType>
 */
class RoomTypeFactory extends Factory
{
    /**
     * Guests per room type (docs/DOMAIN.md: guests 1–6).
     */
    public const CAPACITIES = [
        'Standard' => 2,
        'Deluxe' => 2,
        'Suite' => 4,
        'Family' => 6,
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement(array_keys(self::CAPACITIES));

        return [
            'hotel_id' => Hotel::factory(),
            'name' => $name,
            'capacity' => self::CAPACITIES[$name],
            'total_rooms' => fake()->numberBetween(5, 40),
        ];
    }
}
