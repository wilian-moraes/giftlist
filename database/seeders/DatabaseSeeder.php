<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            HostsTableSeeder::class,
            HostNamesTableSeeder::class,
            ProductsTableSeeder::class,
            ChooseProductsTableSeeder::class,
            GuestNamesTableSeeder::class,
        ]);
    }
}
