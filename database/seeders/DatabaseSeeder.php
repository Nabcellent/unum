<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        Schema::disableForeignKeyConstraints();

        $this->call([
            TeamSeeder::class,
            UserSeeder::class,
            StreamSeeder::class,
            GradeSeeder::class,
            SubjectSeeder::class,
            ExamSeeder::class,
            ResultSeeder::class
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
