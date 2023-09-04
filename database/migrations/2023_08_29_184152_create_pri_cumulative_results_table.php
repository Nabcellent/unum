<?php

use App\Models\Exam;
use App\Models\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pri_cumulative_results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(Exam::class)->constrained()->cascadeOnUpdate();
            $table->smallInteger('total')->nullable();
            $table->json('behaviour')->nullable();
            $table->tinyInteger('rank')->nullable();
            $table->char('conduct', 1)->nullable();
            $table->char('sports_grade', 1)->nullable();
            $table->tinyInteger('attendance')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pri_cumulative_results');
    }
};
