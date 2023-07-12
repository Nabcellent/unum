<?php

namespace App\Http\Controllers;

use App\Models\CumulativeResult;
use App\Models\Exam;
use App\Models\Grade;
use App\Settings\TermSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Grade $grade, TermSetting $termSetting): JsonResponse
    {
        $data = $request->validate(['exam_id' => 'required|exists:exams,id']);

        $students = $grade->students()->with('cumulativeResult', function ($qry) use ($data) {
            return $qry->select(['id', 'student_id', 'exam_id', 'days_attended'])->whereExamId($data['exam_id']);
        })->get(['id', 'grade_id', 'user_id', 'class_no']);

        return response()->json(["students" => $students, "term_days" => $termSetting->days]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createOrEdit(TermSetting $termSetting): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $exams = Exam::get();

        $data = [
            "grades"      => Grade::get(),
            "exams"       => $exams,
            "currentExam" => $exams->firstWhere('name', $termSetting->current_exam),
        ];

        return view('pages.attendances.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function upsert(Request $request, TermSetting $termSetting): JsonResponse
    {
        $attendances = $request->validate([
            '*.id'            => "nullable|exists:cumulative_results",
            '*.student_id'    => "required|exists:students,id",
            '*.exam_id'       => "required|exists:exams,id",
            '*.days_attended' => "required|integer|min:0|max:$termSetting->days",
            '*.total_days'    => "required|integer",
        ]);

        CumulativeResult::upsert($attendances, ['student_id', 'exam_id'], ['days_attended', 'total_days']);

        return response()->json(['status' => 'success', 'msg' => 'Attendances saved successfully!']);
    }
}
