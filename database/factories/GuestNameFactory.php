<?php

namespace Database\Factories;

use App\Models\ChooseProduct;
use App\Models\GuestName;
use Illuminate\Database\Eloquent\Factories\Factory;


class GuestNameFactory extends Factory
{
    protected $model = GuestName::class;

    public function definition(): array
    {
        return [
            'chooseproductid' => ChooseProduct::factory(),
            'name' => $this->faker->name(),
        ];
    }
}
