<?php

namespace Database\Seeders;

use App\Models\Host;
use App\Models\HostName;
use Illuminate\Database\Seeder;

class HostNamesTableSeeder extends Seeder
{

    public function run()
    {
        Host::all()->each(function ($host) {
            HostName::factory()->count(3)->create([
                'hostid' => $host->id,
            ]);
        });
    }
}
