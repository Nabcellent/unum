<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Grade::truncate();

        $grades = [];
        for ($i = 1; $i <= 6; $i++) {
            $grades[] = ["stream_id" => null, "name" => "Grade $i"];
        }

        $grades[] = ["stream_id" => 1, "name" => "Grade 7"];
        $grades[] = ["stream_id" => 2, "name" => "Grade 7"];

        $grades[] = ["stream_id" => null, "name" => "STD 8"];

        for ($i = 1; $i <= 4; $i++) {
            $grades[] = ["stream_id" => 1, "name" => "Form $i"];
            $grades[] = ["stream_id" => 2, "name" => "Form $i"];
        }

        $grades[] = ["stream_id" => null, "name" => "Alumni"];

        Grade::insert($grades);
    }
}
