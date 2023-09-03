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
            CREATE OR REPLACE VIEW cumulative_exam_averages_view AS
            SELECT student_id, YEAR(created_at) AS year, ROUND(AVG(average), 2) AS average
            FROM cumulative_results
            GROUP BY student_id, year
        ");

        DB::statement("
            CREATE OR REPLACE VIEW cumulative_subject_averages_view AS
            SELECT student_id, subject_id, YEAR(created_at) as year, ROUND(AVG(average), 2) AS average
            FROM unum.results
            GROUP BY subject_id, student_id, year;
        ");

        DB::statement("
            CREATE OR REPLACE VIEW learning_area_averages_view AS
            SELECT YEAR(created_at) AS year, student_id, exam_id, la.id AS learning_area_id, ROUND(AVG(mark), 2) AS average
            FROM pri_results
                INNER JOIN unum.indicators i on pri_results.indicator_id = i.id
                INNER JOIN unum.sub_strands ss on i.sub_strand_id = ss.id
                INNER JOIN unum.strands s on ss.strand_id = s.id
                INNER JOIN unum.learning_areas la on s.learning_area_id = la.id
            GROUP BY student_id, exam_id, la.id, year;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS learning_area_averages_view');
        DB::statement('DROP VIEW IF EXISTS cumulative_subject_averages_view');
        DB::statement('DROP VIEW IF EXISTS cumulative_exam_averages_view');
    }
};
