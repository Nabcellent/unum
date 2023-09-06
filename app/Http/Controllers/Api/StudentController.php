<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CumulativeResult;
use App\Models\LearningArea;
use App\Models\PriCumulativeResult;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function getByGradeId(int $gradeId): JsonResponse
    {
        $students = Student::whereGradeId($gradeId)->with('user:id,first_name,last_name')->get([
            'id',
            'user_id',
            'grade_id',
            'admission_no',
            'class_no',
            'dob',
            'created_at'
        ]);

        return $this->successResponse($students);
    }

    public function getPrimaryResults(Request $request, Student $student): JsonResponse
    {
        $data = [
            'results'           => $student->primaryResults()
                ->whereExamId($request->integer('exam_id'))
                ->with('indicator')
                ->get([
                    'id',
                    'indicator_id',
                    'student_id',
                    'mark',
                    'quarter',
                    'rank'
                ]),
            'cumulative_result' => PriCumulativeResult::select([
                'total',
                'average',
                'quarter',
                'passes',
                'conduct',
                'sports_grade',
                'days_absent',
                'total_days'
            ])->firstWhere([
                'exam_id'    => $request->integer('exam_id'),
                'student_id' => $student->id
            ])
        ];

        $learningAreas = $student->grade->learningAreas;

        if($data['results']->count() !== $learningAreas->count()) {
            $data['results'] = $learningAreas->map(function (LearningArea $learningArea) use ($data) {
                $existingResult = $data['results']->firstWhere('learning_area_id', $learningArea->id);

                return $existingResult ?: [
                    "learning_area_id" => $learningArea->id,
                    "learning_area"    => $learningArea
                ];
            });
        }

        return $this->successResponse($data);
    }

    public function getResults(Request $request, Student $student): JsonResponse
    {
        $data = [
            'results'           => $student->secondaryResult()
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
                'days_absent',
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

        return $this->successResponse($data);
    }
}
