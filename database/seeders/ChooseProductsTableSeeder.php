<?php

namespace Database\Seeders;

use App\Models\ChooseProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ChooseProductsTableSeeder extends Seeder
{

    public function run()
    {
        Product::all()->each(function ($product) {
            ChooseProduct::factory()->count(2)->create([
                'productid' => $product->id,
            ]);
        });
    }
}
