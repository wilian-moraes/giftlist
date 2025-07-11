<?php

namespace Database\Factories;

use App\Models\ChooseProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChooseProductFactory extends Factory
{
    protected $model = ChooseProduct::class;

    public function definition(): array
    {
        return [
            'productid' => Product::factory(),
            'userid' => User::factory(),
        ];
    }
}
