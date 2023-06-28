<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::truncate();

        Team::insert([
            ["name" => "Chui"],
            ["name" => "Ndovu"],
            ["name" => "Nyati"],
            ["name" => "Simba"],
        ]);
    }
}
