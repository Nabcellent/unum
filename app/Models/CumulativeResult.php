<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @mixin IdeHelperCumulativeResult
 */
class CumulativeResult extends Model
{
    use HasFactory;

    protected $fillable = [
        "student_id",
        "exam_id",
        "sports_grade",
        "conduct",
        "passes",
        "days_attended",
        "total_days",
    ];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'student_id', 'student_id')
            ->where('exam_id', $this->exam_id);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * @throws Throwable
     */
    public static function updateRankingAndQuarters(int $examId): void
    {
        DB::transaction(function() use ($examId) {
            DB::statement("
                UPDATE cumulative_results AS ar
                    JOIN (SELECT id,
                                 RANK() OVER (PARTITION BY exam_id ORDER BY average DESC) AS `rank`
                          FROM cumulative_results
                          WHERE exam_id = $examId) AS ranks ON ar.id = ranks.id
                SET ar.rank = ranks.rank;
           ");

            DB::statement("
                UPDATE cumulative_results AS ar
                    JOIN (SELECT id, NTILE(4) OVER (PARTITION BY exam_id ORDER BY `rank`) AS quartile
                          FROM cumulative_results
                          WHERE exam_id = $examId) AS quartile_results ON ar.id = quartile_results.id
                SET ar.quarter = quartile_results.quartile;
            ");
        });
    }

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
    public static function updatePasses(): void
    {
        DB::transaction(function() {
            DB::statement("
                UPDATE cumulative_results ar
                SET passes = (
                    SELECT COUNT(*)
                    FROM results r
                    WHERE r.student_id = ar.student_id
                      AND r.exam_id = ar.exam_id
                      AND r.average >= 40
                )
                WHERE EXISTS (
                    SELECT 1
                    FROM results r
                    WHERE r.student_id = ar.student_id
                      AND r.exam_id = ar.exam_id
                      AND r.average >= 40
                );
            ");
        });
    }
}
