<?php

namespace Database\Factories;

use App\Models\Pc;
use Illuminate\Database\Eloquent\Factories\Factory;

class PcFactory extends Factory
{
    protected $model = Pc::class;

    public function definition()
    {
        static $pcNumber = 1;

        return [
            'name' => 'PC' . $pcNumber++,
            'comments' => $this->faker->sentence,
            'is_available' => true,
            'room_id' => null,
        ];
    }
}