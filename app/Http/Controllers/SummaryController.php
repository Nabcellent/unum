<?php

namespace App\Http\Controllers;

use App\Models\CumulativeSubjectAverage;
use App\Models\Exam;
use App\Models\Grade;
use App\Settings\TermSetting;
use Arr;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TCPDF;

class SummaryController extends Controller
{
    public function streamPerformance(TermSetting $termSetting): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $exams = Exam::get();

        $data = [
            "grades"      => Grade::get(),
            "exams"       => $exams,
            "currentExam" => $exams->firstWhere('name', $termSetting->current_exam),
        ];

        return view('pages.summaries.stream-performance', $data);
    }

    public function loadSummaries(array $gradIds, $examId): Collection
    {
        return Grade::with([
            "subjects:id,name,short_name",
            "students" => fn($qry) => $qry->select(['id', 'grade_id', 'user_id', 'class_no'])->whereHas('results')->with([
                'user:id,first_name,middle_name,last_name',
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
                        'days_attended',
                        'total_days'
                    ])->whereExamId($examId)->with('cumulativeExamAverage');
                },
                'results'          => fn($qry) => $qry->select([
                    'id',
                    'student_id',
                    'exam_id',
                    'subject_id',
                    'course_work_mark',
                    'exam_mark',
                    'average',
                    'quarter',
                    'created_at'
                ])->whereExamId($examId)->with(['subject:id,name'])
            ])->orderBy('class_no')])->find($gradIds);
    }

    public function preview(Request $request, Exam $exam): JsonResponse
    {
        $request->validate([
            "grades.*" => "required|exists:grades,id"
        ]);

        $grades = $this->loadSummaries($request->input('grades'), $exam->id);

        if ($grades->isEmpty()) {
            return response()->json(['status' => 'alert', 'msg' => 'No Results available.', 'type' => 'error']);
        }

        try {
            return response()->json([
                "status"    => "success",
                "summaries" => $grades->map(fn(Grade $grade) => [
                    "grade_id" => $grade->id,
                    "html"     => $this->prepareHTML($grade->toArray())
                ])
            ]);
        } catch (Exception $err) {
            return response()->json(['status' => 'alert', 'msg' => $err->getMessage(), 'type' => 'error']);
        }
    }

    public function store(Request $request, Exam $exam, Grade $grade): JsonResponse
    {
        $request->validate([
            "grades.*" => "required|exists:grades,id"
        ]);

        $grades = $this->loadSummaries($request->input('grades'), $exam->id);

        $grades->each(function (Grade $grade) use ($exam) {
            $lineSize = 5;

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "A3", true, "UTF-8", false);

            $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(5, 5, 5);
            $pdf->SetHeaderMargin(3);
            $pdf->SetFooterMargin(5);
            $pdf->SetAutoPageBreak(TRUE, 10);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->SetFont("times", "", 15);
            $pdf->AddPage('L', 'A3');
            $pdf->Cell(150, $lineSize, "EXAM: {$exam->name->value} " . now()->year, 0, 0, 'R');
            $pdf->Cell(250, "$lineSize", "DATE: " . date('l, jS M Y'), 0, 1, 'C');
            $pdf->Ln($lineSize);
            $pdf->SetFont("times", "", 9.5);

            $html = $this->prepareHTML($grade->toArray());

            $pdf->writeHTML($html, true, false, true);

            // Create the directory path recursively if it doesn't exist
            $filePath = public_path("/summaries/" . now()->year . "/{$exam->name->value}/$grade->full_name.pdf");

            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0777, true);
            }

            $pdf->Output($filePath, 'F');
        });

        if ($grade->count() > 1) {
            $message = "Summaries saved successfully!";
        } else {
            $message = "Summary saved successfully!";
        }

        return response()->json(["status" => "success", "msg" => $message]);
    }

    private function prepareHTML(array $grade): string
    {
        $subjectAverages = CumulativeSubjectAverage::whereIn('student_id', Arr::pluck($grade['students'], 'id'))
            ->where('year', now()->year)->get();

        $subjects = collect($grade['subjects'])->add([
            'name'       => 'AVERAGE',
            'short_name' => 'AVE'
        ]);

        $cwid = 70;
        $cwid2 = 26;

        $html = '
                <style>
                    .thick-left-column {
                        border-left-width: 3px;
                        border-bottom-width: 1px;
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

        $html .= '<td align="center" width="' . ($cwid - 7) . '"    style="font-size:9; background-color: lightgrey;">ACC</td></tr><tr><td width="206">NAME</td>';

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
            foreach ($student['results'] as $result) {
                $bgc = getColorForMark($result['average']);

                $cumulativeSubjectAve = $subjectAverages->first(function (CumulativeSubjectAverage $ave) use ($student, $result) {
                    return $ave->student_id === $student['id'] && $ave->subject_id === $result['subject_id'];
                });

                $html .= '<td align="center" style="font-weight: normal; background-color: ' . $bgc . ';">' . $result['average'] . '</td>
                            <td align="center">' . $result['quarter'] . '</td>
                            <td align="center" style="background-color: lightgrey;">' . round($cumulativeSubjectAve->average) . '</td>';

                if ($count === count($student['results']) - 1) {
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

            $html .= "</tr> ";
        }

        $html .= '</table></body></html>';

        return $html;
    }
}
