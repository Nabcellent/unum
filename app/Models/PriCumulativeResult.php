<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @mixin IdeHelperPriCumulativeResult
 */
class PriCumulativeResult extends Model
{
    use HasFactory;

    protected $fillable = [
        "student_id",
        "exam_id",
        "behaviour",
        "conduct",
        "sports_grade",
    ];

    protected $casts = [
        "behaviour" => "array"
    ];

    /**
     * @throws Throwable
     */
    public static function updatePassesRankingAndQuarters(int $examId): void
    {
        self::updatePasses();
        self::updateRankingAndQuarters($examId);
    }

    /**
     * @throws Throwable
     */
    public static function updateRankingAndQuarters(int $examId): void
    {
        DB::transaction(function () use ($examId) {
            DB::statement("
                UPDATE pri_cumulative_results AS ar
                    JOIN (SELECT id,
                                 RANK() OVER (PARTITION BY exam_id ORDER BY average DESC) AS `rank`
                          FROM pri_cumulative_results
                          WHERE exam_id = $examId) AS ranks ON ar.id = ranks.id
                SET ar.rank = ranks.rank;
           ");

            DB::statement("
                UPDATE pri_cumulative_results AS ar
                    JOIN (SELECT id, NTILE(4) OVER (PARTITION BY exam_id ORDER BY `rank`) AS quartile
                          FROM pri_cumulative_results
                          WHERE exam_id = $examId) AS quartile_results ON ar.id = quartile_results.id
                SET ar.quarter = quartile_results.quartile;
            ");
        });
    }

    /**
     * @throws Throwable
     */
    public static function updatePasses(): void
    {
        DB::transaction(function () {
            DB::statement("
                UPDATE pri_cumulative_results ar
                SET passes = (
                    SELECT COUNT(*)
                    FROM pri_results r
                    WHERE r.student_id = ar.student_id
                      AND r.exam_id = ar.exam_id
                      AND r.mark >= 40
                )
                WHERE EXISTS (
                    SELECT 1
                    FROM pri_results r
                    WHERE r.student_id = ar.student_id
                      AND r.exam_id = ar.exam_id
                      AND r.mark >= 40
                );
            ");
        });
    }
}
