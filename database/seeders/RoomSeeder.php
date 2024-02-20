<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creates 3 rooms ad room to add
        Room::create(['name' => 'Room 1']);
        Room::create(['name' => 'Room 2']);
        Room::create(['name' => 'Room 3']);
    }
}
