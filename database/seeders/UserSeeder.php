<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 users
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => "User {$i}",
            ]);
        }
    }
}
