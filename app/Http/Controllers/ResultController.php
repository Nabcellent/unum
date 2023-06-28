<?php

namespace App\Http\Controllers;

use App\Models\AverageResult;
use App\Models\Grade;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function storeSubject(Request $request)
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
        AverageResult::updatePassesRankingAndQuarters($data['exam_id']);

        return response()->json(['status' => 'success', 'msg' => 'Results saved successfully!']);
    }

    /**
     * @throws Throwable
     */
    public function storeStudent(Request $request, Student $student): JsonResponse
    {
        $data = $request->validate([
            "results.*.exam_mark"         => "required|integer|max:99",
            "results.*.course_work_mark"  => "nullable|integer|max:99",
            "results.*.subject_id"        => "required|exists:subjects,id",
            "exam_id"                     => "required|exists:exams,id",
            "average_result.conduct"      => "in:A,B,C,D,E",
            "average_result.sports_grade" => "in:A,B,C,D,E",
        ]);

        $data['results'] = array_map(fn($mark) => [
            ...$mark,
            "student_id" => $student->id,
            "exam_id"    => $data['exam_id'],
        ], $data['results']);

        Result::upsert($data['results'], [], ['course_work_mark', 'exam_mark']);

        $passes = $student->result()->whereExamId($data['exam_id'])->where('average', '>=', 40)->count();
        $averageResult = $student->averageResults()->firstWhere(['exam_id' => $data['exam_id']]);

        if($averageResult->passes != $passes) {
            $data['average_result']['passes'] = $passes;
        }

        $averageResult->update($data['average_result']);

        foreach ($data['results'] as $result) {
            Result::updateRankingAndQuarters($data['exam_id'], $result['subject_id'], $student->grade->name);
        }

        AverageResult::updateRankingAndQuarters($data['exam_id']);

        return response()->json(['status' => 'success', 'msg' => 'Results saved successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Result $result)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Result $result)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result)
    {
        //
    }
}
