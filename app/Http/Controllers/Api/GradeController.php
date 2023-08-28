<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{

    public function getGrades(): JsonResponse
    {
        $grades = Grade::withCount(['students', 'subjects'])->get();

        return $this->successResponse($grades);
    }

    public function getSubjects(Grade $grade): JsonResponse
    {
        return $this->successResponse($grade->subjects);
    }

    public function getLearningAreas(Grade $grade): JsonResponse
    {
        return $this->successResponse($grade->learningAreas);
    }

    public function getResults(Request $request, Grade $grade): JsonResponse
    {
        $data = $grade->students()->with('result', function($qry) use ($request) {
            $qry->select(['id', 'student_id', 'course_work_mark', 'exam_mark', 'average', 'quarter', 'rank'])
                ->whereExamId($request->integer('exam_id'))
                ->whereSubjectId($request->integer('subject_id'));
        })->get(['id', 'grade_id', 'user_id', 'class_no']);

        return $this->successResponse($data);
    }

    public function getStudents(Grade $grade): JsonResponse
    {
        return $this->successResponse($grade->students);
    }
}
