<?php

namespace Database\Seeders;

use App\Models\Pc;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PcSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::all();

        // Create 10 pc's per room
        foreach ($rooms as $room) {
            Pc::factory()->count(10)->create([
                'room_id' => $room->id,
            ]);
        }
    }
}
