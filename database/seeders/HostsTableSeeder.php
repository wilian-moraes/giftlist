<?php

namespace Database\Seeders;

use App\Models\Host;
use App\Models\User;
use Illuminate\Database\Seeder;

class HostsTableSeeder extends Seeder
{

    public function run()
    {
        User::all()->each(function ($user) {
            Host::factory()->count(2)->create([
                'userid' => $user->id,
                'userrevealid' => User::inRandomOrder()->first()->id
            ]);
        });
    }
}
