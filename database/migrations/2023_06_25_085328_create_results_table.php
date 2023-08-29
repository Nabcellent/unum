<?php

use App\Models\Exam;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('results', function(Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(Exam::class)->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(Subject::class)->constrained()->cascadeOnUpdate();
            $table->tinyInteger('course_work_mark')->nullable();
            $table->tinyInteger('exam_mark')->nullable();    //  TODO: Find out if this can be null.
            $table->tinyInteger('average')
                ->storedAs('CASE
                                        WHEN course_work_mark IS NOT NULL THEN ROUND((course_work_mark * 0.3) + (exam_mark * 0.7))
                                        ELSE exam_mark
                                    END');
            $table->tinyInteger('quarter')->nullable();
            $table->tinyInteger('rank')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'exam_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
