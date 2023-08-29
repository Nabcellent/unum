<?php

use App\Models\Exam;
use App\Models\LearningArea;
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
        Schema::create('pri_results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(Exam::class)->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(LearningArea::class)->constrained()->cascadeOnUpdate();
            $table->tinyInteger('mark')->nullable();    //  TODO: Find out if this can be null.
            $table->tinyInteger('quarter')->nullable();
            $table->tinyInteger('rank')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'exam_id', 'learning_area_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pri_results');
    }
};
