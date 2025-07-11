<?php

namespace Database\Seeders;

use App\Models\Host;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{

    public function run()
    {
        Host::all()->each(function ($host) {
            Product::factory()->count(5)->create([
                'hostid' => $host->id,
            ]);
        });
    }
}
