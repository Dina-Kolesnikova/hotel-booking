<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\NightlyInventory;
use App\Models\RoomType;
use App\Models\User;
use Database\Factories\RoomTypeFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Base price per night in cents for a 3-star hotel (CAD).
     */
    private const BASE_PRICES = [
        'Standard' => 15000,
        'Deluxe' => 20000,
        'Suite' => 35000,
        'Family' => 30000,
    ];

    /**
     * Price multiplier by star rating; unrated hotels count as 3 stars.
     */
    private const STAR_MULTIPLIERS = [
        1 => 0.6,
        2 => 0.8,
        3 => 1.0,
        4 => 1.4,
        5 => 2.0,
    ];

    private const DAYS_AHEAD = 90;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Same fake names, cities and prices on every run.
        fake()->seed(2026);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $hotels = Hotel::factory(30)->create();

        foreach ($hotels as $hotel) {
            $typeNames = fake()->randomElements(
                array_keys(RoomTypeFactory::CAPACITIES),
                fake()->numberBetween(2, 4),
            );

            foreach ($typeNames as $typeName) {
                $roomType = RoomType::factory()->for($hotel)->create([
                    'name' => $typeName,
                    'capacity' => RoomTypeFactory::CAPACITIES[$typeName],
                ]);

                $this->createNights($hotel, $roomType);
            }
        }
    }

    /**
     * One row per night for the next DAYS_AHEAD days (docs/DOMAIN.md → Nightly price).
     */
    private function createNights(Hotel $hotel, RoomType $roomType): void
    {
        $starMultiplier = self::STAR_MULTIPLIERS[$hotel->star_rating ?? 3];
        $nights = [];

        for ($day = 0; $day < self::DAYS_AHEAD; $day++) {
            $date = today()->addDays($day);
            $isMidweek = $date->isoWeekday() <= 4; // Mon=1 … Thu=4

            $price = self::BASE_PRICES[$roomType->name] * $starMultiplier;

            if ($isMidweek) {
                $price = $price * 1.2;
            }

            $nights[] = [
                'room_type_id' => $roomType->id,
                'date' => $date->toDateString(),
                'rooms_left' => fake()->boolean(10) ? 0 : fake()->numberBetween(1, $roomType->total_rooms),
                'price' => (int) round($price),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        NightlyInventory::insert($nights);
    }
}
