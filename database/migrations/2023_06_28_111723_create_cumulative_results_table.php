<?php

use App\Models\Exam;
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
        Schema::create('cumulative_results', function(Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(Exam::class)->constrained()->cascadeOnUpdate();
            $table->decimal('average', 4)->nullable();
            $table->tinyInteger('quarter')->nullable();
            $table->tinyInteger('passes')->nullable();
            $table->tinyInteger('rank')->nullable();
            $table->char('conduct', 1)->nullable();
            $table->char('sports_grade', 1)->nullable();
            $table->tinyInteger('days_attended')->nullable();
            $table->tinyInteger('total_days')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cumulative_results');
    }
};
