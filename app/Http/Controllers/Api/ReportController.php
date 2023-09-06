<?php

namespace App\Http\Controllers\Api;

use App\Enums\Level;
use App\Http\Controllers\Controller;
use App\Misc\PDF;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Student;
use App\Settings\TermSetting;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Str;
use TCPDF;

class ReportController extends Controller
{
    public function loadResults(Grade $grade, int $examId, int $studentId = null): Grade
    {
        return $grade->load([
            "students" => function (HasMany $qry) use ($grade, $examId, $studentId) {
                if (isset($studentId)) {
                    $qry = $qry->whereKey($studentId);
                }

                return $qry->select(['id', 'grade_id', 'user_id', 'class_no'])
                    ->with('user:id,first_name,middle_name,last_name')
                    ->when($grade->level === Level::SECONDARY, function (Builder $qry) use ($examId) {
                        return $qry->whereHas('secondaryResults')->with([
                            'cumulativeResult' => function ($qry) use ($examId) {
                                return $qry->select([
                                    'id',
                                    'student_id',
                                    'exam_id',
                                    'average',
                                    'quarter',
                                    'sports_grade',
                                    'conduct',
                                    'passes',
                                    'days_absent',
                                ])->whereExamId($examId);
                            },
                            'secondaryResults' => function ($qry) use ($examId) {
                                return $qry->select([
                                    'id',
                                    'student_id',
                                    'exam_id',
                                    'subject_id',
                                    'course_work_mark',
                                    'exam_mark',
                                    'average',
                                    'quarter'
                                ])->whereExamId($examId)->with(['subject:id,name']);
                            }
                        ]);
                    })
                    ->when($grade->level === Level::PRIMARY, function (Builder $qry) use ($examId) {
                        return $qry->whereHas('learningAreaAverages')->with([
                            'learningAreaAverages' => function (HasMany $qry) use ($examId) {
                                $qry->select(['student_id', 'learning_area_id', 'average'])->whereExamId($examId);
                            },
                            'primaryResults' => function (HasMany $qry) use ($examId) {
                                $qry->select([
                                    'id',
                                    'student_id',
                                    'exam_id',
                                    'indicator_id',
                                    'mark'
                                ])->whereExamId($examId)->with('indicator.subStrand.strand.learningArea');
                            },
                            'primaryCumulativeResult' => function (HasOne $qry) use ($examId) {
                                $qry->select([
                                    'student_id',
                                    'behaviour',
                                    'days_absent',
                                    'total_days'
                                ])->whereExamId($examId);
                            }
                        ]);
                    })
                    ->orderBy('class_no');
            }
        ]);
    }

    private function processPrimaryResults(Grade $grade): Grade
    {
        $grade['students'] = $grade->students->transform(function ($student) {
            $student['learning_area_averages'] = $student->learningAreaAverages->transform(function ($average) use ($student) {
                $results = $student->primaryResults->where('indicator.subStrand.strand.learning_area_id', $average->learning_area_id);

                $average->learning_area = $results->first()->indicator->subStrand->strand->learningArea->name;

                $results = collect($results->toArray())->reduce(function ($carry, $item) {
                    // Extract relevant data
                    $strand = $item['indicator']['sub_strand']['strand'];
                    $subStrand = $item['indicator']['sub_strand'];
                    $indicator = $item['indicator'];
                    $indicator['mark'] = $item['mark'];

                    [$indicator['competency'], $indicator['description'], $indicator['color']] = match (true) {
                        $item['mark'] >= 90 => ["Highly Competent", $indicator["highly_competent"], "#16b300"],
                        $item['mark'] >= 75 => ["Competent", $indicator["competent"], "#0496be"],
                        $item['mark'] >= 60 => [
                            "Approaching Competency",
                            $indicator["approaching_competence"],
                            "yellow"
                        ],
                        $item['mark'] >= 1 => ["Needs Improvement", $indicator["needs_improvement"], "red"],
                        default => ["Not Assessed", "N/A", "darkgrey"],
                    };

                    unset(
                        $strand['learning_area'],
                        $subStrand['strand'],
                        $indicator['sub_strand'],
                        $indicator['highly_competent'],
                        $indicator['competent'],
                        $indicator['approaching_competence'],
                        $indicator['needs_improvement']
                    );

                    // Create a hierarchy if it doesn't exist
                    if (!isset($carry[$strand['id']]))
                        $carry[$strand['id']] = $strand;
                    if (!isset($carry[$strand['id']]['sub_strands'][$subStrand['id']]))
                        $carry[$strand['id']]['sub_strands'][$subStrand['id']] = $subStrand;
                    if (!isset($carry[$strand['id']]['sub_strands'][$subStrand['id']]['indicators'][$indicator['id']]))
                        $carry[$strand['id']]['sub_strands'][$subStrand['id']]['indicators'][$indicator['id']] = $indicator;

                    return $carry;
                }, []);

                [$average['competency'], $average['color']] = match (true) {
                    $average['average'] >= 90 => ["Highly Competent", "#16b300"],
                    $average['average'] >= 75 => ["Competent", "#0496be"],
                    $average['average'] >= 60 => ["Approaching Competency", "yellow"],
                    $average['average'] >= 1 => ["Needs Improvement", "red"],
                    default => ["Not Assessed", "N/A", "darkgrey; color:white;"],
                };

                $average->results = $results;

                return $average->toArray();
            });

            unset($student->primaryResults);

            return $student;
        });

        return $grade;
    }

    public function preview(Request $request, Exam $exam, Grade $grade): JsonResponse
    {
        $request->validate([
            "student_id" => "sometimes|exists:students,id"
        ]);

        $grade = $this->loadResults($grade, $exam->id, $request->input('student_id'));

        if ($grade->level === Level::PRIMARY) {
            $grade = $this->processPrimaryResults($grade);
        }

        if ($grade->students->isEmpty()) {
            return $this->successResponse(msg: 'No Results available.');
        }

        try {
            return $this->successResponse($grade->students->map(fn(Student $student) => [
                "student_id" => $student->id,
                "html"       => $this->prepareHTML($student->toArray(), $grade, $exam)
            ]));
        } catch (Exception $err) {
            return $this->errorResponse($err->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function store(Request $request, Exam $exam, Grade $grade): JsonResponse
    {
        $request->validate(["student_id" => "sometimes|exists:students,id"]);

        $grade = $this->loadResults($grade, $exam->id, $request->input('student_id'));

        if ($grade->level === Level::PRIMARY) {
            $grade = $this->processPrimaryResults($grade);
        }

        $grade->students->each(function (Student $student) use ($exam, $grade) {
            $isPrimary = $grade->level === Level::PRIMARY;
            if ($isPrimary) {
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "A4", true, "UTF-8", false);
                $pdf->SetFooterMargin(5);
            } else {
                $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "A4", true, "UTF-8", false);
            }

            $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(10, 5, 10);
            $pdf->SetHeaderMargin($isPrimary ? 4 : 15);
            $pdf->SetAutoPageBreak(TRUE, 10);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->SetFont("times", "", $isPrimary ? 9 : 15);

            $pdf->AddPage('P', 'A4');

            $html = $this->prepareHTML($student->toArray(), $grade, $exam);

            $pdf->writeHTML($html, true, false, true);

            // Create the directory path recursively if it doesn't exist
            $filePath = public_path("/reports/" . now()->year . "/{$exam->name->value}/$grade->full_name/$student->id.pdf");

            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0777, true);
            }

            $pdf->Output($filePath, 'F');
        });

        if ($grade->students->count() > 1) {
            $message = "Reports saved successfully!";
        } else {
            $message = "Report saved successfully!";
        }

        return $this->successResponse(msg: $message);
    }

    /**
     * @throws Exception
     */
    private function prepareHTML(array $student, Grade $grade, Exam $exam): string
    {
        $date = app(TermSetting::class)->report_exam_date;

        if (!$date) {
            throw new Exception('Report exam date has not been set.');
        }

        if ($grade->level === Level::SECONDARY) {
            $classAverage = round($exam->cumulativeResults()->withWhereHas('student', function ($qry) use ($grade) {
                return $qry->whereHas('grade', function ($qry) use ($grade) {
                    return $qry->whereName($grade->name);
                });
            })->avg('average'), 2);

            return lower_secondary_report($student, $grade->full_name, $exam->name->value, $date, $classAverage);
        } else {
            $gradeName = Str::replace('Grade ', '', $grade->name);

            return primary_report($student, $gradeName, $exam->name->value, $date);
        }
    }
}
