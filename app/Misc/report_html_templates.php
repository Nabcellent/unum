<?php

use App\Enums\Exam;
use App\Settings\TermSetting;
use Carbon\Carbon;

function primary_report(array $student, string $grade, string $exam, Carbon $date)
{
//    dd($student);
    $html = '<!DOCTYPE html>
                <html lang="en-gb">
                <head>
                    <title>REPORTS</title>
                    <style type="text/css">
                        h1 {
                            text-align: center;
                        }
                        form{
                            text-align: center;
                        }
                        body{
                            background: rgb(204,204,204);
                            width: auto;
                            margin: auto;
                        }
                        page[size="A4"] {
                            background: white;
                            display: block;
                            margin: 0 auto 0.5cm;
                            box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
                            padding: 50px;
                            width: 21cm;
                            height: 29.7cm;
                        }
                        .mainline {
                            margin:20px auto auto;
                            border: 0.4mm solid black;
                            height: 0.1mm;
                            background: black;
                        }
                        .mini_line {
                            margin: auto auto auto 0;
                            border-color: black;
                        }
                        .clear {
                            clear: both;
                            display: block;
                            content: "";
                            width: 100%;
                        }
                        h3, h5 {
                            margin: auto;
                        }
                        h4 {
                            display: inline;
                            margin: auto;
                            width: 85px;
                        }
                    </style>
                </head>
                <body>
                    <table border="0" cellspacing="3" cellpadding="2">
                    <tr>
                        <td colspan="3" width="470px" height="40px"  style="font-family: times; font-weight: bold; font-size: 24pt;" >STRATHMORE SCHOOL</td>
                        <td rowspan="4" width="175px"><img src="/images/strath_logo.gif"  alt="strathmore logo" align="right" height="150" width="150"></td>
                    </tr>
                    <tr>
                        <td colspan="3" height="75px" style="color:darkgray; font-family: times; font-weight: normal; font-size: 21pt;">ACHIEVEMENT REPORTS</td>
                    </tr>
                    <tr>
                        <td width="60px"  style="font-family:times; font-size:12pt; font-weight: bold;">NAME</td><td width="10px">:</td><td width="400" valign="middle" style="font-family:times; font-size:11pt; font-weight: bold;">' . $student['full_name'] . '</td>
                    </tr>
                    <tr>
                      <td  style="font-family:times; font-size:11pt; font-weight: bold;">GRADE</td><td>:</td><td  style="font-family:times; font-size:11pt; font-weight: bold;">' . $grade . '</td>
                    </tr>
                    <tr height="12">
                        <td width="175px"></td>
                        <td width="210px"></td>
                        <td width="100px"  style="font-family:times; font-size:11pt; font-weight: bold;">CLASS NO.</td>
                        <td width="10px">:</td>
                        <td width="150px"  style="font-family:times; font-size:11pt; font-weight: bold;">' . $student['class_no'] . '</td>
                    </tr>
                    <tr height="12">
                        <td width="175px"></td>
                        <td width="210px"></td>
                        <td width="100px" style="font-family:times; font-size:11pt; font-weight: bold;">ASSESSMENT</td>
                        <td width="10px">:</td>
                        <td width="150px" style="font-family:times; font-size:11pt; font-weight: bold;" >' . $exam . '</td>
                    </tr>
                    <tr height="10">
                        <td width="175px"></td>
                        <td width="210px"></td>
                        <td width="100px"  style="font-family:times; font-size:11pt; font-weight: bold;">DATE</td>
                        <td width="10px">:</td>
                        <td width="150px"  style="font-family:times; font-size:10pt; font-weight: bold;" >' . $date->format('j') . '<sup>' . strtoupper($date->format('S')) . '</sup> ' . strtoupper($date->format(' F Y')) . '</td>
                    </tr>
                    <tr height="5">
                        <td width="175px"></td>
                        <td width="300px"></td>
                        <td width="175px"></td>
                    </tr>
                    </table>
                ';

    foreach ($student['learning_area_averages'] as $laAverage) {
        $html .= '<table border="0" cellspacing="3" cellpadding="0">
                    <tr style="font-family:times; font-size:14pt" nobr="true">
                        <td rowspan="2" width="450px" style="font-size:17pt;"><b>' . $laAverage['learning_area']['name'] . '</b>
                        </td>
                        <td align="center" width="197px" style="font-family: times; font-weight: bold; font-size: 11pt; background-color: ' . $laAverage['color'] . '">
                        ' . round($laAverage['average'], 0) . '
                        </td>
                    </tr>
                    <tr nobr="true">
                        <td colspan=2 width="197px" align="centre" height="15pt" style="font-family: times; font-weight: bold; font-size: 11pt; background-color: ' . $laAverage['color'] . '">' . $laAverage['competency'] . '</td>
                        <td  width="325px"></td>
                    </tr>';

        $strandCount = 0;
        foreach ($laAverage['results'] as $key => $result) {
            $strandColSpan = $strandCount === 0 ? 2 : 6;
            $strandCount++;

            $html .= '<tr style="color:white;font-family:times">
                        <td colspan="' . $strandColSpan . '" bgcolor="darkgray">
                            &nbsp;<br/>&nbsp;&nbsp;&nbsp;<b>' . $result['name'] . '</b><br/>
                        </td>
                    </tr>
                    <tr><td></td></tr>
                    <tr style="color:gray;font-weight:bold;font-size:10pt">
                        <td width="10"></td>
                        <td width="185">TOPIC</td>
                        <td width="10"></td>
                        <td width="280">DESCRIPTION OF ACHIEVEMENT</td>
                        <td width="10"></td>
                        <td>ATTAINED LEVEL</td>
                    </tr>
                    <tr><td></td></tr>';

            foreach ($result['sub_strands'] as $subStrand) {
                foreach ($subStrand['indicators'] as $indicator) {
                    $subStrandName = $indicator['sub_strand_id'] === $subStrand['id'] ? $subStrand['name'] : " ";

                    $html .= '
                        <tr style="font-size:9;font-family:times;">
                            <td width="10"></td>
                            <td rowspan="2" style="color:#2B2B70;font-weight:bold"><br/>' . $subStrandName . '</td>
                            <td rowspan="2" width="10"></td>
                            <td rowspan="2"><i style="color:gray">' . $indicator['name'] . '</i><br/>' . $indicator['description'] . '</td>
                            <td rowspan="2" width="10"></td>
                            <td width="140" align="center" valign="middle" height="20pt"
                                style="background-color:' . $indicator['color'] . ';font-weight:bold;">' . $indicator['competency'] . ' <br/>' . $indicator['mark'] . '
                            </td>
                        </tr>
                        <tr><td></td></tr>
                    ';
                }
            }

            $html .= "<tr><td></td></tr>";
        }

        $html .= '</table>';
    }

    return $html;
}

function lower_secondary_report(array $student, string $grade, string $exam, Carbon $date, int $classAverage): string
{
    $nextTermDate = app(TermSetting::class)->next_term_date;

    if (in_array($exam, [
            Exam::CAT_2->value,
            Exam::CAT_4->value,
            Exam::CAT_6->value
        ]) && $nextTermDate) {
        $term = "Next term begins : " . $nextTermDate;
    } else {
        $term = '&nbsp;';
    }

    $promotion = '&nbsp;';

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
                            <td width="340" valign="middle" style="font-family:times,serif; font-size:13pt; font-weight: bold;">' . $student['full_name'] . '</td>
                            <td width="90px"  style="font-family:times; font-size:13pt; font-weight: bold;">CLASS</td>
                            <td width="10px">:</td>
                            <td width="80px"  style="font-family:times; font-size:13pt; font-weight: bold;">' . $grade . '</td>
                        </tr>
                        <tr>
                            <td  style="font-family:times; font-size:13pt; font-weight: bold;">ASSESSMENT</td>
                            <td>:</td>
                            <td style="font-family:times; font-size:13pt; font-weight: bold;">' . $exam . '</td>
                            <td style="font-family:times; font-size:13pt; font-weight: bold;">CLASS NO.</td>
                              <td >:</td>
                             <td style="font-family:times; font-size:13pt; font-weight: bold;" >' . $student['class_no'] . '</td>
                        </tr>
                         <tr>
                             <td style="font-family:times; font-size:13pt; font-weight: bold;">DATE</td>
                             <td>:</td>
                             <td width="200px"  style="font-family:times; font-size:13pt; font-weight: bold;">' . $date->format('j') . '<sup>' . strtoupper($date->format('S')) . '</sup> ' . strtoupper($date->format(' F Y')) . '
                             </td>
                            <td  colspan="3" align="right" width="320px" style="font-family:times; font-size:13pt; font-weight: bold;">' . $term . '</td>

                         </tr>
                        <tr>
                             <td  width="645px" colspan="6">' . $promotion . '</td>
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
						 <td width="421" style="background-color:#EAEBEC;font-size:13.0pt; font-family: Times;color:black"><b>&nbsp;&nbsp;&nbsp;&nbsp;' . $result['subject']['name'] . '</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>' . $result['course_work_mark'] . '</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>' . $result['exam_mark'] . '</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>' . $result['average'] . '</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>' . $result['quarter'] . '</b></td>
					</tr>';
        $h = $h + 4;
    }

    $html .= '<tr style="page-break-inside:avoid;height:22pt">
						 <td width="421" style="background-color:#48A8F1;font-size:13.0pt; font-family: Times;color:black"><b>&nbsp;&nbsp;&nbsp;&nbsp;AVERAGE</b></td>
						 <td width="56" style="background-color:#48A8F1;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#48A8F1;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#48A8F1;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>' . $student['cumulative_result']['average'] . '</b></td>
						 <td width="56" style="background-color:#48A8F1;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>' . $student['cumulative_result']['quarter'] . '</b></td>
					</tr>
					<tr style="page-break-inside:avoid;height:17pt">
						 <td width="421" style="background-color:#FFFFFF;font-size:13.0pt; font-family: Times;color:black"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PASSES</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>' . $student['cumulative_result']['passes'] . '</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
					</tr>
					<tr style="page-break-inside:avoid;height:17pt">
						 <td width="421" style="background-color:#FFFFFF;font-size:13.0pt; font-family: Times;color:black"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CLASS AVERAGE</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#000000"><b>' . $classAverage . '</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
					</tr>
                    <tr style="page-break-inside:avoid;height:17pt">
						 <td width="421" style="background-color:#EAEBEC;font-size:13.0pt; font-family: Times;color:black"><b>&nbsp;&nbsp;&nbsp;&nbsp;SPORTS</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#000000"><b>' . $student['cumulative_result']['sports_grade'] . '</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
					</tr>
                    <tr style="page-break-inside:avoid;height:17pt">
						 <td width="421" style="background-color:#EAEBEC;font-size:13.0pt; font-family: Times;color:black"><b>&nbsp;&nbsp;&nbsp;&nbsp;CONDUCT</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#000000"><b>' . $student['cumulative_result']['conduct'] . '</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
						 <td width="56" style="background-color:#EAEBEC;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
					</tr>
                </table>
                <table border="0" cellspacing="1" cellpadding="2" >
                    <tr><td  height="25px" width="200">&nbsp;</td></tr>
                    <tr>
                        <td width="200" valign="bottom" style="font-family: times; font-size: 13pt; font-weight: bold; text-align:left; " >';

    if ($exam !== Exam::CAT_6->value) {
        $html .= ' <u><img src="/images/signatures/akm.jpg"  alt="HoS sign..." align="left" height="49" ></u><br>Head of Section';
    } else {
        $html .= ' <u><img src="/images/signatures/jm.jpg"  alt="Principal sign..." align="left" height="51"></u><br>Principal';
    }

    $html .= '</td>
                    <td width="120" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
                    <td width="170" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:black"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ATTENDANCE:</b></td>
                    <td width="120" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:black">
                        <b><i>' . $student['cumulative_result']['days_attended'] . ' of ' . $student['cumulative_result']['total_days'] . '</i> days</b>
                    </td>
                    <td width="50" style="background-color:#FFFFFF;font-size:13.0pt; text-align:center; font-family: Times;color:#2E74B5"><b>&nbsp;</b></td>
                </tr>
            </table>
        </html>';

    return $html;
}
