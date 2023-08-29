<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE TRIGGER after_insert_calculate_average_sec
                AFTER INSERT
                ON results
                FOR EACH ROW
            BEGIN
                DECLARE total DECIMAL(8, 2);
                DECLARE count INT;

                -- Calculate the total marks and count of exams for the student
                SELECT SUM(average), COUNT(*)
                INTO total, count
                FROM results
                WHERE student_id = NEW.student_id
                  AND exam_id = NEW.exam_id AND average IS NOT NULL;

                -- Calculate the average mark
                SET total = IFNULL(total, 0);
                SET count = IFNULL(count, 0);
                SET @average = total / count;

                -- Insert or update the average in the averages table
                INSERT INTO cumulative_results (student_id, exam_id, average, created_at)
                VALUES (NEW.student_id, NEW.exam_id, @average, NOW())
                ON DUPLICATE KEY UPDATE average = @average;
            END;
        ");
        DB::statement("
            CREATE TRIGGER after_update_calculate_average_sec
                AFTER UPDATE
                ON results
                FOR EACH ROW
            BEGIN
                DECLARE total DECIMAL(8, 2);
                DECLARE count INT;

                -- Calculate the total marks and count of exams for the student
                SELECT SUM(average), COUNT(*)
                INTO total, count
                FROM results
                WHERE student_id = NEW.student_id
                  AND exam_id = NEW.exam_id AND average IS NOT NULL;

                -- Calculate the average mark
                SET total = IFNULL(total, 0);
                SET count = IFNULL(count, 0);
                SET @average = total / count;

                -- Insert or update the average in the averages table
                INSERT INTO cumulative_results (student_id, exam_id, average, created_at)
                VALUES (NEW.student_id, NEW.exam_id, @average, NOW())
                ON DUPLICATE KEY UPDATE average = @average, updated_at = NOW();
            END;
        ");

        DB::statement("
            CREATE TRIGGER after_insert_calculate_average_pri
                AFTER INSERT
                ON pri_results
                FOR EACH ROW
            BEGIN
                DECLARE total DECIMAL(8, 2);
                DECLARE count INT;

                -- Calculate the total marks and count of exams for the student
                SELECT SUM(mark), COUNT(*)
                INTO total, count
                FROM pri_results
                WHERE student_id = NEW.student_id
                  AND exam_id = NEW.exam_id AND mark IS NOT NULL;

                -- Calculate the average mark
                SET total = IFNULL(total, 0);
                SET count = IFNULL(count, 0);
                SET @average = total / count;

                -- Insert or update the average in the averages table
                INSERT INTO pri_cumulative_results (student_id, exam_id, total, average, created_at)
                VALUES (NEW.student_id, NEW.exam_id, total, @average, NOW())
                ON DUPLICATE KEY UPDATE average = @average, total = total;
            END;
        ");
        DB::statement("
            CREATE TRIGGER after_update_calculate_average_pri
                AFTER UPDATE
                ON pri_results
                FOR EACH ROW
            BEGIN
                DECLARE total DECIMAL(8, 2);
                DECLARE count INT;

                -- Calculate the total marks and count of exams for the student
                SELECT SUM(mark), COUNT(*)
                INTO total, count
                FROM pri_results
                WHERE student_id = NEW.student_id
                  AND exam_id = NEW.exam_id AND mark IS NOT NULL;

                -- Calculate the average mark
                SET total = IFNULL(total, 0);
                SET count = IFNULL(count, 0);
                SET @average = total / count;

                -- Insert or update the average in the averages table
                INSERT INTO pri_cumulative_results (student_id, exam_id, total, average, created_at)
                VALUES (NEW.student_id, NEW.exam_id, total, @average, NOW())
                ON DUPLICATE KEY UPDATE average = @average, total = total, updated_at = NOW();
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TRIGGER IF EXISTS after_update_calculate_average_sec;");
        DB::statement("DROP TRIGGER IF EXISTS after_insert_calculate_average_sec;");
        DB::statement("DROP TRIGGER IF EXISTS after_update_calculate_average_pri;");
        DB::statement("DROP TRIGGER IF EXISTS after_insert_calculate_average_pri;");

        Schema::dropIfExists('triggers');
    }
};
