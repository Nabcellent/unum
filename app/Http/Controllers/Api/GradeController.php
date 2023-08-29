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
        $grades = Grade::withCount(['students', 'subjects', 'learningAreas'])->get();

        return $this->successResponse($grades);
    }

    public function getSubjects(Grade $grade): JsonResponse
    {
        return $this->successResponse($grade->subjects);
    }

    public function syncSubjects(Request $request, Grade $grade): JsonResponse
    {
        $ids = $request->validate([
            'subjects.*' => 'required|integer|distinct|exists:subjects,id'
        ]);

        $grade->subjects()->sync($ids['subjects']);

        return $this->successResponse(msg: 'Subjects Assigned Successfully');
    }

    public function syncLearningAreas(Request $request, Grade $grade): JsonResponse
    {
        $ids = $request->validate([
            'learning_areas.*' => 'required|integer|distinct|exists:learning_areas,id'
        ]);

        $grade->learningAreas()->sync($ids['learning_areas']);

        return $this->successResponse(msg: 'Subjects Assigned Successfully');
    }

    public function getLearningAreas(Grade $grade): JsonResponse
    {
        return $this->successResponse($grade->learningAreas);
    }

    public function getResults(Request $request, Grade $grade): JsonResponse
    {
        $isPrimary = $grade->level === 'primary';

        $data = $grade->students()
            ->when(!$isPrimary, function ($qry) use ($request) {
                $qry->with('result', function ($qry) use ($request) {
                    $qry->select(['id', 'student_id', 'course_work_mark', 'exam_mark', 'average', 'quarter', 'rank'])
                        ->whereExamId($request->integer('exam_id'))
                        ->whereSubjectId($request->integer('subject_id'));
                });
            })
            ->when($isPrimary, function ($qry) use ($request) {
                $qry->with('primaryResult', function ($qry) use ($request) {
                    $qry->select(['id', 'student_id', 'mark', 'quarter', 'rank'])
                        ->whereExamId($request->integer('exam_id'))
                        ->whereLearningAreaId($request->integer('learning_area_id'));
                });
            })->get(['id', 'grade_id', 'user_id', 'class_no']);

        return $this->successResponse($data);
    }

    public function getStudents(Grade $grade): JsonResponse
    {
        return $this->successResponse($grade->students);
    }
}
