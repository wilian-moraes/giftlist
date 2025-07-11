<?php

namespace Database\Factories;

use App\Models\HostName;
use App\Models\Host;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class HostNameFactory extends Factory
{
    protected $model = HostName::class;

    public function definition()
    {
        return [
            'hostid' => Host::factory(),
            'name' => $this->faker->words(2, true),
        ];
    }
}
