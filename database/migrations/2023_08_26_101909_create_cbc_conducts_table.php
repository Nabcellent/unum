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
        Schema::create('cbc_conducts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Exam::class)->constrained()->cascadeOnUpdate();
            $table->string('work', 15);
            $table->string('self_respect', 15);
            $table->string('courtesy', 15);
            $table->string('participates', 15);
            $table->string('cooperates', 15);
            $table->string('enthusiastic', 15);
            $table->string('sets_goals', 15);
            $table->string('confidence', 15);
            $table->char('conduct', 1)->nullable();
            $table->char('sports_grade', 1)->nullable();
            $table->tinyInteger('attendance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbc_conducts');
    }
};
