<?php

use App\Models\Grade;
use App\Models\LearningArea;
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
        Schema::create('grade_learning_area', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Grade::class);
            $table->foreignIdFor(LearningArea::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_learning_area');
    }
};
