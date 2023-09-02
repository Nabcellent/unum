<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Grade;
use App\Settings\TermSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    public function indexPri(TermSetting $termSettings): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $exams = Exam::get();

        $data = [
            "grades"      => Grade::primary()->get(),
            "exams"       => $exams,
            "currentExam" => $exams->firstWhere('name', $termSettings->current_exam),
        ];

        return view('pages.primary.reports.index', $data);
    }

    public function indexSec(TermSetting $termSettings): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $exams = Exam::get();

        $data = [
            "grades"      => Grade::secondary()->get(),
            "exams"       => $exams,
            "currentExam" => $exams->firstWhere('name', $termSettings->current_exam),
        ];

        return view('pages.secondary.reports.index', $data);
    }
}
