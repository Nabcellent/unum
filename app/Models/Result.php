<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @mixin IdeHelperResult
 */
class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exam_id',
        'subject_id',
        'course_work_mark',
        'exam_mark',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function averageResult(): BelongsTo
    {
        return $this->belongsTo(AverageResult::class, 'student_id', 'student_id')
            ->whereColumn('exam_id', '=', 'exam_id');
    }

    /**
     * @throws Throwable
     */
    public static function updateRankingAndQuarters(int $examId, int $subjectId, string $grade = null): void
    {
        self::updateRanking($grade, $examId, $subjectId);
        self::updateQuarters($examId, $subjectId);
    }

    /**
     * @throws Throwable
     */
    public static function updateRanking(string $grade = null, int $examId = null, int $subjectId = null): void
    {
        DB::transaction(function() use ($examId, $subjectId, $grade) {
            $qry = "
                UPDATE results AS r
                    JOIN (
                        SELECT id,
                               grade,
                               student_id,
                               subject_id,
                               exam_id,
                               average,
                               @rank := IF(@prev_grade = grade AND @prev_subject = subject_id AND @prev_exam = exam_id, @rank + 1, 1) AS `rank`,
                               @prev_grade := grade,
                               @prev_subject := subject_id,
                               @prev_exam := exam_id
                        FROM (SELECT r.id,
                                     g.name AS grade,
                                     s.id   AS student_id,
                                     r.subject_id,
                                     r.exam_id,
                                     r.average
                              FROM grades g
                                       INNER JOIN students s ON s.grade_id = g.id
                                       INNER JOIN results r ON r.student_id = s.id
            ";

            if ($grade) $qry .= "AND g.name = '$grade' ";
            if ($subjectId) $qry .= "AND r.subject_id = $subjectId ";
            if ($examId) $qry .= "AND r.exam_id = $examId ";

            $qry .= "ORDER BY g.name, r.subject_id, r.exam_id, r.average DESC) AS subquery
                                 CROSS JOIN (SELECT @rank := 0, @prev_grade := NULL, @prev_subject := NULL, @prev_exam := NULL) AS vars
                    ) AS ranked_results ON r.id = ranked_results.id
                SET r.rank = ranked_results.`rank`;";

            DB::statement($qry);
        });
    }

    /**
     * @throws Throwable
     */
    public static function updateQuarters(int $examId, int $subjectId): void
    {
        DB::transaction(function() use ($examId, $subjectId) {
            $qry = "
                UPDATE results AS r
    JOIN (SELECT r.id,
                 r.student_id,
                 s.grade_id,
                 r.subject_id,
                 r.exam_id,
                 average,
                 FLOOR((ROW_NUMBER() OVER (PARTITION BY s.grade_id, r.subject_id, r.exam_id ORDER BY r.`rank`) - 1) /
                       (g.grade_count / 4)) + 1 AS quarter
          FROM results r
                   JOIN students s ON r.student_id = s.id
                   JOIN (SELECT grade_id, name, COUNT(*) AS grade_count
                         FROM results r
                                  JOIN students s ON r.student_id = s.id
                                  JOIN grades g2 ON s.grade_id = g2.id
                         WHERE r.exam_id = $examId
                           AND r.subject_id = $subjectId
                           AND r.average IS NOT NULL
                         GROUP BY grade_id, name) g ON s.grade_id = g.grade_id
          WHERE exam_id = $examId
            AND subject_id = $subjectId
            AND average IS NOT NULL) AS quarters ON r.id = quarters.id
SET r.quarter = quarters.`quarter`;
            ";

            DB::statement($qry);
        });
    }
}
