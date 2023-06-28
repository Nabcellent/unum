<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Grade;
use App\Settings\ExamSettings;

class AssessmentController extends Controller
{
    public function getStudent(ExamSettings $examSettings)
    {
        $data = [
            "grades"      => Grade::get(),
            "exams"       => Exam::get(['id', 'name']),
            "currentExam" => $examSettings->current,
        ];

        return view('pages.marks.student', $data);
    }

    public function getSubject(ExamSettings $examSettings)
    {
        $data = [
            "grades"      => Grade::get(),
            "exams"       => Exam::get(['id', 'name']),
            "currentExam" => $examSettings->current,
        ];

        return view('pages.marks.subject', $data);
    }
}
