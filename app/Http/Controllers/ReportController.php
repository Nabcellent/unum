<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamDate;
use App\Models\Grade;
use App\Models\Student;
use App\Settings\ExamSettings;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(ExamSettings $examSettings): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $data = [
            "grades"      => Grade::get(),
            "exams"       => Exam::get(['id', 'name']),
            "currentExam" => $examSettings->current,
        ];

        return view('pages.reports.index', $data);
    }

    public function preview(Request $request, Exam $exam, Grade $grade, ExamSettings $examSettings)
    {
        $data = $request->validate([
            "student_id" => "sometimes|exists:students,id"
        ]);
        $data['exam_id'] = $exam->id;

        $grade->load([
            "students" => function($qry) use ($data) {
                if (isset($data['student_id'])) {
                    $qry = $qry->whereKey($data['student_id']);
                }

                return $qry->select(['id', 'grade_id', 'user_id', 'class_no'])->with([
                    'user:id,first_name,middle_name,last_name',
                    'averageResult' => function($qry) use ($data) {
                        return $qry->select(['id', 'student_id', 'exam_id', 'average', 'quarter', 'sports_grade', 'conduct', 'passes'])
                            ->whereExamId($data['exam_id']);
                    },
                    'results'       => function($qry) use ($data) {
                        return $qry->select(['id', 'student_id', 'exam_id', 'subject_id', 'course_work_mark', 'exam_mark', 'average', 'quarter'])
                            ->whereExamId($data['exam_id'])->with(['subject:id,name']);
                    }
                ])->orderBy('class_no');
            }
        ]);

        return json_encode(["reports" => $grade->students->map(fn(Student $student) => [
            "student_id" => $student->id,
            "html"       => $this->prepareHTML($student->toArray(), $grade, $exam)
        ])]);
    }

    private function prepareHTML(array $student, Grade $grade, Exam $exam)
    {
        $examDates = ExamDate::latest('class')->first();

        $date = $examDates->report_exam_date;
        $nextTermDate = $examDates->report_next_term;

        $promotion = '&nbsp;';

        if (in_array($exam->name, [\App\Enums\Exam::CAT_2, \App\Enums\Exam::CAT_4, \App\Enums\Exam::CAT_6]) && $nextTermDate) {
            $term = "Next term begins : ".$nextTermDate;
        } else {
            $term = '&nbsp;';
        }

        $classAverage = round($exam->averageResults()->withWhereHas('student', function($qry) use ($grade) {
            return $qry->whereHas('grade', function($qry) use ($grade) {
                return $qry->whereName($grade->name);
            });
        })->avg('average'), 2);

        $html = '<html>
					<table border="0" cellspacing="3" cellpadding="2">
                        <tr>
                            <td rowspan="2"  colspan="2" width="175px"><img src="/images/strath_logo.gif"  alt="strathmore logo" align="right" height="105" width="105"></td>
                            <td colspan="4" width="470px" height="35px" align="center" style="font-family: times,serif; font-weight: bold; font-size: 24pt;" >STRATHMORE SCHOOL</td>
                        </tr>
                        <tr>
                            <td colspan="4" width="470px" height="75px" valign="top" align="center"  style=" color:darkgrey; font-family: times,serif; font-size: 21pt; font-weight: bold;">ACADEMIC REPORT</td>
                        </tr>
                        <tr>
                            <td width="115px"  style="font-family:times,serif; font-size:13pt; font-weight: bold;">NAME</td>
                            <td width="10px">:</td>
                            <td width="340" valign="middle" style="font-family:times,serif; font-size:13pt; font-weight: bold;">'.$student['full_name'].'</td>
                            <td width="90px"  style="font-family:times; font-size:13pt; font-weight: bold;">CLASS</td>
                            <td width="10px">:</td>
                            <td width="80px"  style="font-family:times; font-size:13pt; font-weight: bold;">'.$grade->full_name.'</td>
                        </tr>
                        <tr>
                            <td  style="font-family:times; font-size:13pt; font-weight: bold;">ASSESSMENT</td>
                            <td>:</td>
                            <td style="font-family:times; font-size:13pt; font-weight: bold;">'.$exam->name->value.'</td>
                            <td style="font-family:times; font-size:13pt; font-weight: bold;">CLASS NO.</td>
                              <td >:</td>
                             <td style="font-family:times; font-size:13pt; font-weight: bold;" >'.$student['class_no'].'</td>
                        </tr>
                         <tr>
                             <td style="font-family:times; font-size:13pt; font-weight: bold;">DATE</td>
                             <td>:</td>
                             <td width="200px"  style="font-family:times; font-size:13pt; font-weight: bold;">
                             '.$date->format('j').'<sup>'.strtoupper($date->format('S')).'</sup> '.strtoupper($date->format(' F Y')).'
                             </td>
                            <td  colspan="3" align="right" width="320px" style="font-family:times; font-size:13pt; font-weight: bold;">'.$term.'</td>

                         </tr>
                        <tr >
                             <td  width="645px" colspan="6">'.$promotion.'</td>
                        </tr>
                    </table>

					<table  border="0" cellspacing="4" cellpadding="2" >
					 <tr style="page-break-inside:avoid;height:28.5pt">
						 <td width="421" style="background-color:#FFFFFF;font-size:1.2pt; padding:0cm 0cm 0cm 0cm; font-family: Times;color:black"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:1.20pt; padding:0cm 0cm 0cm 0cm; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:1.2pt; padding:0cm 0cm 0cm 0cm; text-align:center; font-family: Times;color:#000000"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:1.2pt; padding:0cm 0cm 0cm 0cm; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:1.2pt; padding:0cm 0cm 0cm 0cm; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
					</tr>
					 <tr style="page-break-inside:avoid;height:45.5pt">
						 <td width="421" style="background-color:#8496B0;font-size:14.0pt; font-family: Times;color:white"><b>LEARNING AREA</b></td>
						 <td width="56" style="background-color:#8496B0;font-size:14.0pt; text-align:center; font-family: Times;color:white"><b>CW</b></td>
						 <td width="56" style="background-color:#8496B0;font-size:14.0pt; text-align:center; font-family: Times;color:white"><b>EX</b></td>
						 <td width="56" style="background-color:#8496B0;font-size:14.0pt; text-align:center; font-family: Times;color:white"><b>%</b></td>
						 <td width="56" style="background-color:#8496B0;font-size:14.0pt; text-align:center; font-family: Times;color:white"><b>QRT</b></td>
					</tr>';

        $h = 6;
        foreach ($student['results'] as $result) {
            $html .= '<tr style="page-break-inside:avoid;height:22pt">
						 <td width="421" style="background-color:#EAEBEC;font-size:13.0pt; font-family: Times;color:black"><b>&nbsp;&nbsp;&nbsp;&nbsp;'.$result['subject']['name'].'</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>'.$result['course_work_mark'].'</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>'.$result['exam_mark'].'</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>'.$result['average'].'</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>'.$result['quarter'].'</b></td>
					</tr>';
            $h = $h + 4;
        }

        $html .= '<tr style="page-break-inside:avoid;height:22pt">
						 <td width="421" style="background-color:#48A8F1;font-size:13.0pt; font-family: Times;color:black"><b>&nbsp;&nbsp;&nbsp;&nbsp;AVERAGE</b></td>
						 <td width="56" style="background-color:#48A8F1;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#48A8F1;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#48A8F1;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>'.$student['average_result']['average'].'</b></td>
						 <td width="56" style="background-color:#48A8F1;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>'.$student['average_result']['quarter'].'</b></td>
					</tr>

					<tr style="page-break-inside:avoid;height:17pt">
						 <td width="421" style="background-color:#FFFFFF;font-size:13.0pt; font-family: Times;color:black"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PASSES</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>'.$student['average_result']['passes'].'</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
					</tr>
					<tr style="page-break-inside:avoid;height:17pt">
						 <td width="421" style="background-color:#FFFFFF;font-size:13.0pt; font-family: Times;color:black"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CLASS AVERAGE</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#000000"><b>'.$classAverage.'</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
					</tr>
					 <tr style="page-break-inside:avoid;height:17pt">
						 <td width="421" style="background-color:#EAEBEC;font-size:13.0pt; font-family: Times;color:black"><b>&nbsp;&nbsp;&nbsp;&nbsp;SPORTS</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#000000"><b>'.$student['average_result']['sports_grade'].'</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
					</tr>
					 <tr style="page-break-inside:avoid;height:17pt">
						 <td width="421" style="background-color:#EAEBEC;font-size:13.0pt; font-family: Times;color:black"><b>&nbsp;&nbsp;&nbsp;&nbsp;CONDUCT</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#000000"><b>'.$student['average_result']['conduct'].'</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
					</tr>
                </table>
                <table border="0" cellspacing="1" cellpadding="2" >
                    <tr><td  height="25px" width="200">&nbsp;</td></tr>
                    <tr><td width="200" valign="bottom" style="font-family: times; font-size: 13pt; font-weight: bold; text-align:left; " >';

        if ($exam->name != \App\Enums\Exam::CAT_6) {
            $html .= ' <u><img src="/images/signatures/akm.jpg"  alt="HoS sign..." align="left" height="49" ></u><br>Head of Section';
        } else {
            $html .= ' <u><img src="/images/signatures/jm.jpg"  alt="Principal sign..." align="left" height="51"></u><br>Principal';
        }

        $html .= '</td></tr>
            </table>
        </html>';

        return $html;
    }
}
