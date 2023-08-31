<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @mixin IdeHelperPriResult
 */
class PriResult extends Model
{
    use HasFactory;

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function subStrand(): BelongsTo
    {
        return $this->belongsTo(SubStrand::class);
    }

    /**
     * @throws Throwable
     */
    public static function updateRankingAndQuarters(int $examId, int $subStrandId, string $grade = null): void
    {
        self::updateRanking($grade, $examId, $subStrandId);
        self::updateQuarters($examId, $subStrandId);
    }

    /**
     * @throws Throwable
     */
    public static function updateRanking(string $grade = null, int $examId = null, int $subStrandId = null): void
    {
        DB::transaction(function () use ($examId, $subStrandId, $grade) {
            $qry = "
                UPDATE pri_results AS r
                    JOIN (
                        SELECT id,
                               grade,
                               student_id,
                               sub_strand_id,
                               exam_id,
                               mark,
                               @rank := IF(@prev_grade = grade AND @prev_subject = sub_strand_id AND @prev_exam = exam_id, @rank + 1, 1) AS `rank`,
                               @prev_grade := grade,
                               @prev_subject := sub_strand_id,
                               @prev_exam := exam_id
                        FROM (SELECT r.id,
                                     g.name AS grade,
                                     s.id   AS student_id,
                                     r.sub_strand_id,
                                     r.exam_id,
                                     r.mark
                              FROM grades g
                                       INNER JOIN students s ON s.grade_id = g.id
                                       INNER JOIN pri_results r ON r.student_id = s.id
            ";

            if ($grade) $qry .= "AND g.name = '$grade' ";
            if ($subStrandId) $qry .= "AND r.sub_strand_id = $subStrandId ";
            if ($examId) $qry .= "AND r.exam_id = $examId ";

            $qry .= "ORDER BY g.name, r.sub_strand_id, r.exam_id, r.mark DESC) AS subquery
                                 CROSS JOIN (SELECT @rank := 0, @prev_grade := NULL, @prev_subject := NULL, @prev_exam := NULL) AS vars
                    ) AS ranked_results ON r.id = ranked_results.id
                SET r.rank = ranked_results.`rank`;";

            DB::statement($qry);
        });
    }

    /**
     * @throws Throwable
     */
    public static function updateQuarters(int $examId, int $subStrandId): void
    {
        DB::transaction(function () use ($examId, $subStrandId) {
            $qry = "
                UPDATE pri_results AS r
    JOIN (SELECT r.id,
                 r.student_id,
                 s.grade_id,
                 r.sub_strand_id,
                 r.exam_id,
                 mark,
                 FLOOR((ROW_NUMBER() OVER (PARTITION BY s.grade_id, r.sub_strand_id, r.exam_id ORDER BY r.`rank`) - 1) /
                       (g.grade_count / 4)) + 1 AS quarter
          FROM pri_results r
                   JOIN students s ON r.student_id = s.id
                   JOIN (SELECT grade_id, name, COUNT(*) AS grade_count
                         FROM pri_results r
                                  JOIN students s ON r.student_id = s.id
                                  JOIN grades g2 ON s.grade_id = g2.id
                         WHERE r.exam_id = $examId
                           AND r.sub_strand_id = $subStrandId
                           AND r.mark IS NOT NULL
                         GROUP BY grade_id, name) g ON s.grade_id = g.grade_id
          WHERE exam_id = $examId
            AND sub_strand_id = $subStrandId
            AND mark IS NOT NULL) AS quarters ON r.id = quarters.id
SET r.quarter = quarters.`quarter`;
            ";

            DB::statement($qry);
        });
    }
}
