<?php

namespace Database\Seeders;

use App\Models\Stream;
use Illuminate\Database\Seeder;

class StreamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Stream::truncate();

        Stream::insert([
            ["name" => "A", "full_name" => "A"],
            ["name" => "&", "full_name" => "Alpha"]
        ]);
    }
}
