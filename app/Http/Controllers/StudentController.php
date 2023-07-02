<?php

namespace App\Http\Controllers;

use App\Models\CumulativeResult;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $data = [
            "grades" => Grade::get(['id', 'stream_id', 'name'])
        ];

        return view('pages.students.index', $data);
    }

    public function getByGradeId(int $gradeId): JsonResponse
    {
        $students = Student::whereGradeId($gradeId)->with('user:id,first_name,last_name')->get(['id', 'user_id', 'grade_id', 'admission_no', 'class_no', 'dob', 'created_at']);

        return response()->json(['status' => true, 'students' => $students]);
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
            'results'           => $student->results()
                ->whereExamId($request->integer('exam_id'))
                ->with('subject')
                ->get([
                    'id',
                    'subject_id',
                    'student_id',
                    'course_work_mark',
                    'exam_mark',
                    'average',
                    'quarter',
                    'rank'
                ]),
            'cumulative_result' => CumulativeResult::select([
                'average',
                'quarter',
                'passes',
                'conduct',
                'sports_grade',
                'days_attended',
                'total_days'
            ])->firstWhere([
                'exam_id'    => $request->integer('exam_id'),
                'student_id' => $student->id
            ])
        ];

        if ($data['results']->isEmpty()) {
            $data['results'] = $student->grade->subjects->map(fn(Subject $subject) => [
                "subject_id" => $subject->id,
                "subject"    => $subject
            ]);
        }

        return response()->json($data);
    }
}
