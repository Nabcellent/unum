<?php

namespace App\Http\Controllers;

use App\Models\CumulativeResult;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
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
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }

    public function results(Request $request, Student $student): JsonResponse
    {
        $data = [
            'results'           => $student->results()->whereExamId($request->integer('exam_id'))->with('subject')
                ->get(['id', 'subject_id', 'student_id', 'course_work_mark', 'exam_mark', 'average', 'quarter', 'rank']),
            'cumulative_result' => CumulativeResult::select(['average', 'quarter', 'passes', 'conduct', 'sports_grade', 'days_attended'])
                ->firstWhere([
                    'exam_id'    => $request->integer('exam_id'),
                    'student_id' => $student->id
                ])
        ];

        return response()->json($data);
    }
}
