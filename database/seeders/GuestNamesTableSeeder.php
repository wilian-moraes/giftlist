<?php

namespace Database\Seeders;

use App\Models\ChooseProduct;
use App\Models\GuestName;
use Illuminate\Database\Seeder;

class GuestNamesTableSeeder extends Seeder
{

    public function run()
    {
        ChooseProduct::all()->each(function ($chooseProduct) {
            GuestName::factory()->count(1)->create([
                'chooseproductid' => $chooseProduct->id,
            ]);
        });
    }
}
