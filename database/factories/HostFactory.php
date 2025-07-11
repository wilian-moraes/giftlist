<?php

namespace Database\Factories;

use App\Models\Host;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class HostFactory extends Factory
{
    protected $model = Host::class;

    public function definition()
    {
        $eventDate = $this->faker->dateTimeBetween('now', '+1 year');
        $closeList = (clone $eventDate)->modify('-1 week');

        return [
            'userid' => User::factory(),
            'eventdate' => $eventDate,
            'closelist' => $closeList,
            'shownames' => $this->faker->boolean(),
            'userrevealid' => User::factory(),
        ];
    }
}
