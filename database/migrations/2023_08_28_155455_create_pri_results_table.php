<?php

use App\Models\Exam;
use App\Models\Indicator;
use App\Models\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pri_results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(Exam::class)->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(Indicator::class)->constrained()->cascadeOnUpdate();
            $table->tinyInteger('mark')->nullable();
            $table->tinyInteger('rank')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'exam_id', 'indicator_id']);
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
