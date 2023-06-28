<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subject::truncate();
        DB::table('grade_subject')->truncate();

        $subjects = [
            ["short_name" => "ENG", "name" => "ENGLISH"],
            ["short_name" => "KIS", "name" => "KISWAHILI"],
            ["short_name" => "MAT", "name" => "MATHEMATICS"],
            ["short_name" => "ISC", "name" => "INTEGRATED SCIENCE"],
            ["short_name" => "PRE", "name" => "PRE TECHNICAL"],
            ["short_name" => "HEE", "name" => "HEALTH EDUCATION"],
            ["short_name" => "AGR", "name" => "AGRICULTURE"],
            ["short_name" => "SST", "name" => "SOCIAL STUDIES"],
            ["short_name" => "BUS", "name" => "BUSINESS STUDIES"],
            ["short_name" => "LSK", "name" => "LIFE SKILLS"],
            ["short_name" => "CRE", "name" => "CHRISTIAN RELIGIOUS EDUCATION"],
            ["short_name" => "PES", "name" => "PHYSICAL EDUCATION AND SPORTS"],
            ["short_name" => "ART", "name" => "VISUAL ARTS"],
            ["short_name" => "FRE", "name" => "FRENCH"],
            ["short_name" => "COM", "name" => "COMPUTER SCIENCE"],
        ];

        Subject::insert($subjects);

        [$grade7A, $grade7Alpha] = Grade::whereName('Grade 7')->pluck('id');

        $gradeSubjects = [];
        for ($i = 1; $i <= count($subjects); $i++) {
            $gradeSubjects[] = ["grade_id" => $grade7A, "subject_id" => $i];
            $gradeSubjects[] = ["grade_id" => $grade7Alpha, "subject_id" => $i];
        }

        DB::table('grade_subject')->insert($gradeSubjects);
    }
}
