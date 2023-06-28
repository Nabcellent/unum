<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();
        Student::truncate();

        $users = [
            [
                "id"         => 1,
                "first_name" => "Nabcellent",
                "last_name"  => "Nabangi",
                "email"      => "nabcellent.dev@gmail.com",
                "password"   => Hash::make(12345678),
            ]
        ];

        $parents = [
            [
                "id"         => 2,
                "first_name" => "Herbert",
                "last_name"  => "Nabangi",
                "email"      => "herbert@gmail.com",
                "password"   => Hash::make(12345678),
            ], [
                "id"         => 3,
                "first_name" => "Jacinta",
                "last_name"  => "Nabangi",
                "email"      => "jacinta@gmail.com",
                "password"   => Hash::make(12345678),
            ],
        ];

        User::insert([...$users, ...$parents]);

        /*$students = [];
        foreach (Grade::pluck('id') as $gradeId) {
            $classNo = 1;

            for ($i = 1; $i <= 4; $i++) {
                for ($j = 0; $j < 3; $j++) {
                    $students[] = [
                        "user_id"      => User::factory()->create()->id,
                        "grade_id"     => $gradeId,
                        "team_id"      => $i,
                        "admission_no" => fake()->numerify('####'),
                        "class_no"     => $classNo,
                        "dob"          => fake()->dateTimeBetween(),
                        "citizenship"  => "KENYAN",
                        "religion"     => fake()->randomElement([
                            'ADVENTIST',
                            'AGC',
                            'ANGLICAN',
                            'BAPTIST',
                            'CATHOLIC', 'CATHOLIC', 'CATHOLIC',
                            'ISLAM',
                            'JUBILEE CHRISTI',
                            'KAG',
                            'MUSLIM',
                            'PENTECOSTAL',
                            'PROTESTANT',
                            'PCEA',
                            'PRESBYTERIAN',
                        ])
                    ];

                    $classNo++;
                }
            }
        }

        Student::insert($students);*/
    }
}
