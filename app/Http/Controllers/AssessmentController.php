<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Grade;
use App\Settings\ExamSettings;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AssessmentController extends Controller
{
    public function getStudent(ExamSettings $examSettings): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $data = [
            "grades"      => Grade::get(),
            "exams"       => Exam::get(['id', 'name']),
            "currentExam" => $examSettings->current,
        ];

        return view('pages.marks.student', $data);
    }

    public function getSubject(ExamSettings $examSettings): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $data = [
            "grades"      => Grade::get(),
            "exams"       => Exam::get(['id', 'name']),
            "currentExam" => $examSettings->current,
        ];

        return view('pages.marks.subject', $data);
    }
}
