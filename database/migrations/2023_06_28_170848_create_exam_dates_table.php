<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_dates', function(Blueprint $table) {
            $table->id();
            $table->string('class', 10);
            $table->date('entry_completed');
            $table->date('quarters_completed');
            $table->date('reports_completed');
            $table->date('report_exam_date');
            $table->date('report_next_term')->nullable();
            $table->tinyInteger('cat_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_dates');
    }
};
