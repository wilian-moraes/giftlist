<?php

namespace Database\Factories;

use App\Models\Host;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $imageData = 'data:image/png;base64,' . base64_encode(random_bytes(128));

        return [
            'hostid' => Host::factory(),
            'name' => $this->faker->words(1, true),
            'productimg' => $imageData,
            'link' => $this->faker->url()
        ];
    }
}
