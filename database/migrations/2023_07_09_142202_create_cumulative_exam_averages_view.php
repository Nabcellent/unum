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
            CREATE VIEW cumulative_exam_averages_view AS
            SELECT student_id, YEAR(created_at) AS year, ROUND(AVG(average), 2) AS average
            FROM cumulative_results
            GROUP BY student_id, year
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS cumulative_exam_averages_view');
    }
};
