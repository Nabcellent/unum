<?php

namespace Database\Seeders;

use App\Models\Exam;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Exam::truncate();

        $exams = [];
        foreach (\App\Enums\Exam::cases() as $exam) {
            $exams[] = ["name" => $exam->value];
        }

        Exam::insert($exams);
    }
}
