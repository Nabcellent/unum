<?php

namespace App\Http\Controllers;

use App\Models\CumulativeResult;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Result;
use App\Models\Student;
use App\Settings\TermSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class SecResultController extends Controller
{
    public function getView(string $view, TermSetting $termSetting): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $exams = Exam::get();

        $data = [
            "grades"      => Grade::secondary()->get(),
            "exams"       => $exams,
            "currentExam" => $exams->firstWhere('name', $termSetting->current_exam),
        ];

        if ($view === 'student') {
            $data['catDays'] = $termSetting->cat_days;
        }

        return view("pages.secondary.marks.$view", $data);
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function storeSubject(Request $request): JsonResponse
    {
        $data = $request->validate([
            "marks.*.id"               => "integer",
            "marks.*.exam_mark"        => "required|integer|max:99",
            "marks.*.course_work_mark" => "integer|max:99",
            "marks.*.student_id"       => "required|exists:students,id",
            "exam_id"                  => "required|exists:exams,id",
            "subject_id"               => "required|exists:subjects,id",
            "grade_id"                 => "required|exists:grades,id",
        ]);

        $data['marks'] = array_map(fn($mark) => [
            ...$mark,
            "exam_id"    => $data['exam_id'],
            "subject_id" => $data['subject_id'],
        ], $data['marks']);

        Result::upsert($data['marks'], [], ['course_work_mark', 'exam_mark']);

        $grade = Grade::find($data['grade_id']);

        Result::updateRankingAndQuarters($data['exam_id'], $data['subject_id'], $grade->name);
        CumulativeResult::updatePassesRankingAndQuarters($data['exam_id']);

        return response()->json(['status' => 'success', 'msg' => 'Results saved successfully!']);
    }

    /**
     * @throws Throwable
     */
    public function storeStudent(Request $request, Student $student): JsonResponse
    {
        $data = $request->validate([
            "results.*.exam_mark"             => "required|integer|max:99",
            "results.*.course_work_mark"      => "nullable|integer|max:99",
            "results.*.subject_id"            => "required|exists:subjects,id",
            "exam_id"                         => "required|exists:exams,id",
            "cumulative_result.conduct"       => "nullable|in:A,B,C,D,E",
            "cumulative_result.sports_grade"  => "nullable|in:A,B,C,D,E",
            "cumulative_result.days_attended" => "nullable|integer",
            "cumulative_result.total_days"    => "nullable|integer",
        ]);

        $data['results'] = array_map(fn($mark) => [
            ...$mark,
            "student_id" => $student->id,
            "exam_id"    => $data['exam_id'],
        ], $data['results']);

        Result::upsert($data['results'], [], ['course_work_mark', 'exam_mark']);

        $passes = $student->result()->whereExamId($data['exam_id'])->where('average', '>=', 40)->count();
        $cumulativeResult = $student->cumulativeResults()->firstWhere(['exam_id' => $data['exam_id']]);

        if ($cumulativeResult->passes != $passes) {
            $data['cumulative_result']['passes'] = $passes;
        }

        $cumulativeResult->update($data['cumulative_result']);

        foreach ($data['results'] as $result) {
            Result::updateRankingAndQuarters($data['exam_id'], $result['subject_id'], $student->grade->name);
        }

        CumulativeResult::updateRankingAndQuarters($data['exam_id']);

        return response()->json(['status' => 'success', 'msg' => 'Results saved successfully!']);
    }
}
