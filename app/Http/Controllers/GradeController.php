<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Stream;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $data = [
            "streams" => Stream::all()
        ];

        return view('pages.classes.index', $data);
    }

    public function getGrades(): JsonResponse
    {
        $grades = Grade::withCount(['students', 'subjects'])->get();

        return response()->json(['status' => true, 'grades' => $grades]);
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
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        //
    }

    public function students(Grade $grade): JsonResponse
    {
        return response()->json($grade->students);
    }

    public function subjects(Grade $grade): JsonResponse
    {
        return response()->json($grade->subjects);
    }

    public function results(Request $request, Grade $grade): JsonResponse
    {
        $data = $grade->students()->with('result', function($qry) use ($request) {
            $qry->select(['id', 'student_id', 'course_work_mark', 'exam_mark', 'average', 'quarter', 'rank'])
                ->whereExamId($request->integer('exam_id'))
                ->whereSubjectId($request->integer('subject_id'));
        })->get(['id', 'grade_id', 'user_id', 'class_no']);

        return response()->json($data);
    }
}
