<?php

namespace App\Http\Controllers;

use App\Models\CumulativeResult;
use App\Models\CumulativeSubjectAverage;
use App\Models\Exam;
use App\Models\Grade;
use App\Settings\TermSetting;
use Arr;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TCPDF;

class SummaryController extends Controller
{
    public function classPerformance(TermSetting $termSetting): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $exams = Exam::get();

        $data = [
            "grades"      => Grade::secondary()->select('name')->distinct()->get(),
            "exams"       => $exams,
            "currentExam" => $exams->firstWhere('name', $termSetting->current_exam),
        ];

        return view('pages.secondary.summaries.class-performance', $data);
    }

    public function loadSummaries(string $grades, $examId): array
    {
        $grades = Grade::with([
            "subjects:id,name,short_name",
            "students" => fn($qry) => $qry->select([
                'id',
                'grade_id',
                'user_id',
                'class_no'
            ])->whereHas('secondaryResults')->with([
                'user:id,first_name,middle_name,last_name',
                'cumulativeResult' => function ($qry) use ($examId) {
                    return $qry->select([
                        'id',
                        'student_id',
                        'exam_id',
                        'average',
                        'quarter',
                        'conduct',
                        'passes',
                    ])->whereExamId($examId)->with('cumulativeExamAverage');
                },
                'secondaryResults' => fn($qry) => $qry->select([
                    'id',
                    'student_id',
                    'exam_id',
                    'subject_id',
                    'course_work_mark',
                    'exam_mark',
                    'average',
                    'quarter',
                    'created_at'
                ])->whereExamId($examId)->with('subject')
            ])->orderBy('class_no')
        ])->whereName($grades)->get();

        return [
            $grades,

            $grades->mapWithKeys(function ($g) {
                $count = $g->students->filter(function ($student) {
                    $x = collect($student['secondaryResults'])->filter(function ($result) {
                        return in_array($result['subject']['name'], [
                                'ENGLISH',
                                'KISWAHILI'
                            ]) && $result['average'] >= 40;
                    })->count();

                    return $student->cumulativeResult->passes < 9 || $x < 2;
                })->count();

                return [$g['id'] => $count];
            })
        ];
    }

    public function preview(Request $request, Exam $exam): JsonResponse
    {
        $request->validate(["grade" => "required|exists:grades,name"]);

        [$grades, $belowPromotion] = $this->loadSummaries($request->string('grade'), $exam->id);

        if ($grades->isEmpty()) {
            return response()->json(['status' => 'alert', 'msg' => 'No Results available.', 'type' => 'error']);
        }

        $classAverages = CumulativeResult::join('students', 'student_id', '=', 'students.id')
            ->join('grades', 'students.grade_id', '=', 'grades.id')
            ->select(DB::raw('AVG(average) as average, grades.id'))
            ->where('exam_id', $exam->id)
            ->groupBy('grades.id')
            ->get();

        try {
            return $this->successResponse($grades->map(fn(Grade $grade) => [
                "grade_id" => $grade->id,
                "html"     => $this->prepareHTML($grade->toArray(), $classAverages, $belowPromotion)
            ]));
        } catch (Exception $err) {
            return $this->errorResponse($err->getMessage());
        }
    }

    public function store(Request $request, Exam $exam): JsonResponse
    {
        $data = $request->validate([
            "grade"    => "required|exists:grades,name",
            "grade_id" => "nullable|integer|exists:grades,id",
        ]);

        [$grades, $belowPromotion] = $this->loadSummaries($data['grade'], $exam->id);

        $pdfName = $data['grade'];
        if ($request->filled('grade_id')) {
            $grades = $grades->where('id', $data['grade_id']);
            $pdfName = $grades->first()->full_name;
        }

        $classAverages = CumulativeResult::join('students', 'student_id', '=', 'students.id')
            ->join('grades', 'students.grade_id', '=', 'grades.id')
            ->select(DB::raw('AVG(average) as average, grades.id'))
            ->where('exam_id', $exam->id)
            ->groupBy('grades.id')
            ->get();

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "A3", true, "UTF-8", false);

        $lineSize = 3;

        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(5, 5, 5);
        $pdf->SetHeaderMargin(3);
        $pdf->SetFooterMargin(5);
        $pdf->SetAutoPageBreak(TRUE, 10);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont("times", "", 15);

        foreach ($grades as $grade) {
            $pdf->AddPage('L', 'A3');
            $pdf->Cell(50, $lineSize, $grade->full_name, 0, 0, 'R');
            $pdf->Cell(150, $lineSize, "EXAM: {$exam->name->value} " . now()->year, 0, 0, 'R');
            $pdf->Cell(250, "$lineSize", "DATE: " . date('l, jS M Y'), 0, 1, 'C');
            $pdf->Ln($lineSize);
            $pdf->SetFont("times", "", 9.5);

            $html = $this->prepareHTML($grade->toArray(), $classAverages, $belowPromotion);

            $pdf->writeHTML($html, true, false, true);
        }

        $filePath = public_path("/summaries/" . now()->year . "/{$exam->name->value}/$pdfName.pdf");

        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        $pdf->Output($filePath, 'F');

        return $this->successResponse(
            msg: $grades->count() > 1 ? "Summaries saved successfully!" : "Summary saved successfully!"
        );
    }

    private function prepareHTML(array $grade, $classAverages, \Illuminate\Support\Collection $belowPromotion): string
    {
        $subjectAverages = CumulativeSubjectAverage::whereIn('student_id', Arr::pluck($grade['students'], 'id'))
            ->where('year', now()->year)->get();

        $subjects = collect($grade['subjects'])->add(['name' => 'AVERAGE', 'short_name' => 'AVE']);
        $students = collect($grade['students']);

        $cwid = 70;
        $cwid2 = 26;

        $html = '
                <style>
                    .thick-left-column {
                        border-left-width: 3px;
                        border-bottom-width: 1px;
                    }
                    .border-1 {
                        border: 1px solid black;
                    }
                </style>
                <html><body><table border="1" cellpadding="0" cellspacing="0">
                <tr><td width="206" style="background-color: lightgrey; font-weight: bold; border-style: none solid solid none;">' . strtoupper($grade['full_name']) . '</td>';

        foreach ($subjects as $subject) {
            $class = '';
            if ($subject['name'] === "AVERAGE") {
                $cwidas = $cwid + 26;
                $cps = 5;
                $class .= 'thick-left-column';
            } else {
                $cwidas = $cwid;
                $cps = 3;
            }

            $html .= '<td colspan="' . $cps . '" width="' . $cwidas . '" class="' . $class . '" align="center" valign="middle" style="background-color: lightgrey;">' . $subject['short_name'] . '</td>';
        }

        $html .= '<td align="center" width="' . ($cwid - 7) . '"    style="font-size:9; background-color: lightgrey;">ACC</td></tr><tr><td width="206">NAMES</td>';

        foreach ($subjects as $subject) {
            if ($subject['name'] === "AVERAGE") {
                $hhf = '<td align="center" width="22" style="font-size: 7;">PASS</td> ';
                $hhfe = '<td align="center" width="20" style="font-size: 7;" class="thick-left-column">CON</td>';
                $cwida = $cwid2 + 10;
            } else {
                $hhfe = '';
                $hhf = '';
                $cwida = $cwid2;
            }

            $html .= $hhfe . '<td align="center" width="' . $cwida . '">%</td><td align="center" width="18">Q</td>' . $hhf . '<td width="' . $cwida . '" align="center" style="background-color: lightgrey;">AV</td>';
        }

        $html .= '<td align="center" width="27" style="font-size: 7;">PASS</td></tr>';

        foreach ($grade['students'] as $student) {
            $html .= '<tr><td width="206" height="17.5" >';
            $html .= $student['class_no'] < 10 ? "&nbsp;&nbsp;&nbsp;&nbsp;" : "&nbsp;&nbsp;";
            $html .= "{$student['class_no']}. {$student['name']}</td>";

            $count = 0;
            foreach ($student['secondary_results'] as $result) {
                $bgc = getColorForMark($result['average']);

                $cumulativeSubjectAve = $subjectAverages->first(function (CumulativeSubjectAverage $ave) use ($student, $result) {
                    return $ave->student_id === $student['id'] && $ave->subject_id === $result['subject_id'];
                });

                $html .= '<td align="center" style="font-weight: normal; background-color: ' . $bgc . ';">' . $result['average'] . '</td>
                            <td align="center">' . $result['quarter'] . '</td>
                            <td align="center" style="background-color: lightgrey;">' . round($cumulativeSubjectAve->average) . '</td>';

                if ($count === count($student['secondary_results']) - 1) {
                    $bgc = getColorForMark($student['cumulative_result']['average']);

                    $passes = $subjectAverages->filter(function (CumulativeSubjectAverage $ave) use ($student) {
                        return $ave['student_id'] === $student['id'] && round($ave['average']) >= 40;
                    })->count();

                    $html .= '<td align="center" style="font-weight: bold;" class="thick-left-column">' . $student['cumulative_result']['conduct'] . '</td>
                        <td align="center"  style="font-weight: bold; background-color: ' . $bgc . ';" >' . number_format($student['cumulative_result']['average'], 2) . '</td>
                        <td align="center" style="font-weight: bold; ">' . $student['cumulative_result']['quarter'] . '</td>
                        <td align="center" style="font-weight: bold; ">' . $student['cumulative_result']['passes'] . '</td>
                        <td align="center"  width="' . ($cwid2 + 10) . '" style="background-color: lightgrey; font-weight: bold;">' . $student['cumulative_result']['cumulative_exam_average']['average'] . '</td>
                        <td align="center"  width="' . ($cwid2 + 1) . '" style="font-weight: bold;  ">' . $passes . '</td>
                    ';
                }

                $count++;
            }

            $html .= "</tr>";
        }

        $subjectAverages = $students
            ->flatMap(fn($item) => $item['secondary_results'])
            ->groupBy('subject_id')
            ->map(fn($res) => round($res->sum('average') / $res->count(), 2))
            ->toArray();

        $html .= '<tr><td width="206" height="17.5"></td>';

        foreach ($subjects as $subject) {
            if ($subject['name'] === 'AVERAGE') {
                $avg = round($students->avg('cumulative_result.average'), 2);
                $html .= '<td style="font-weight: bold;"></td>';
                $html .= '<td colspan="5" style="font-weight:bolder;"> ' . $avg . '</td>';
            } else {
                $html .= '<td colspan="3" style="font-weight:bolder;"> ' . $subjectAverages[$subject['id']] . '</td>';
            }
        }

        $html .= '</tr></table>';

        $html .= '<table border="0">
                    <tr style="page-break-inside:avoid;height:40px">
                         <td width="300" height="20" style="background-color:#FFFFFF;font-size:1.2pt; padding:0cm 0cm 0cm 0cm; font-family: Times;color:black"><b>&nbsp;</b></td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="table">';

        $studentsCount = $students->count();

        $above70 = [];
        $above60 = [];
        $above50 = [];
        $above40 = [];
        $below40 = [];

        foreach ($subjects as $subject) {
            $percent = function ($from, $to) use ($studentsCount, $subject, $students) {
                $count = $students->filter(function ($s) use ($students, $to, $from, $subject) {
                    if ($subject['short_name'] === "AVE") {
                        $ave = $s['cumulative_result']['average'];
                    } else {
                        $ave = Arr::first($s['secondary_results'], function ($res) use ($subject) {
                            return $res['subject']['short_name'] === $subject['short_name'];
                        })['average'];
                    }

                    return $ave >= $from && $ave <= $to;
                })->count();

                return round(($count / $studentsCount) * 100, 1);
            };

            $above70[] = $percent(70, 100);
            $above60[] = $percent(60, 69);
            $above50[] = $percent(50, 59);
            $above40[] = $percent(40, 49);
            $below40[] = $percent(0, 39);
        }

        $stats = [
            [
                "title_a" => "",
                "columns" => $subjects->map(fn($s) => $s['short_name']),
                "title_b" => ["label" => "BELOW PROMOTION (Class)", "value" => $belowPromotion[$grade['id']]],
            ],
            [
                "title_a" => "PERCENTAGE ABOVE 70%",
                "columns" => $above70,
                "title_b" => ["label" => "BELOW PROMOTION (Stream)", "value" => $belowPromotion->sum()]
            ],
            [
                "title_a" => "PERCENTAGE BETWEEN 60% & 69%",
                "columns" => $above60,
                "title_b" => [
                    "label" => "CLASS AVERAGE (Class)",
                    "value" => round($classAverages->find($grade['id'])->average, 2)
                ]
            ],
            [
                "title_a" => "PERCENTAGE BETWEEN 50% & 59%",
                "columns" => $above50,
                "title_b" => ["label" => "CLASS AVERAGE (Stream)", "value" => round($classAverages->avg('average'), 2)]
            ],
            ["title_a" => "PERCENTAGE BETWEEN 40% & 49%", "columns" => $above40],
            ["title_a" => "PERCENTAGE BELOW 40%", "columns" => $below40],
        ];

        for ($i = 0; $i < count($stats); $i++) {
            $html .= '<tr>';
            $html .= '<td width="210" class="border-1" style="background-color: lightgrey;"> ' . $stats[$i]['title_a'] . '</td>';

            foreach ($stats[$i]['columns'] as $key => $col) {
                $bgColor = '';

                if ($i === 0) $bgColor = 'lightgrey';

                $html .= '<td width="30" align="center" valign="middle" class="border-1" style="background-color: ' . $bgColor . ';">' . $col . '</td>';

                //  Adds spaces between columns
                if ($i === 0 && $key !== 0 && $key % 2 === 1) $html .= '<td width="20" rowspan="6" class="border-1" style="background-color: lightgrey;"></td>';
            }

            if ($i === 0) $html .= '<td width="330" rowspan="6"></td>';

            if (isset($stats[$i]['title_b'])) {
                $html .= '
                    <td width="200" class="border-1" style="background-color: lightgrey;"> ' . $stats[$i]['title_b']['label'] . '</td>
                    <td width="35" align="center" class="border-1">' . $stats[$i]['title_b']['value'] . '</td>
                ';
            }

            $html .= "</tr>";
        }

        $html .= '</table></body></html>';

        return $html;
    }
}
