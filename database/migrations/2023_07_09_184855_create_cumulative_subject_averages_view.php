<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW cumulative_subject_averages_view AS
            SELECT student_id, subject_id, YEAR(created_at) as year, ROUND(AVG(average), 2) AS average
            FROM unum.results
            GROUP BY subject_id, student_id, year;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS cumulative_subject_averages_view');
    }
};
