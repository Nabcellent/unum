<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Grade;
use App\Settings\TermSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class PriResultController extends Controller
{
    public function getView(string $view, TermSetting $termSetting): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $exams = Exam::get();

        $data = [
            "grades"      => Grade::primary()->get(),
            "exams"       => $exams,
            "currentExam" => $exams->firstWhere('name', $termSetting->current_exam),
        ];

        if ($view === 'student') {
            $data['termDays'] = $termSetting->days;
        }

        return view("pages.marks.primary.$view", $data);
    }
}
