<?php

namespace App\Http\Controllers;

use App\Enums\Level;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\CumulativeResult;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\PriCumulativeResult;
use App\Settings\TermSetting;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
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

        $students = $grade->students()
            ->when($grade->level === Level::PRIMARY, function (Builder $qry) use ($data) {
                $qry->with('primaryCumulativeResult', function ($qry) use ($data) {
                    return $qry->select([
                        'id',
                        'student_id',
                        'exam_id',
                        'days_absent'
                    ])->whereExamId($data['exam_id']);
                });
            })
            ->when($grade->level === Level::SECONDARY, function (Builder $qry) use ($data) {
                $qry->with('cumulativeResult', function ($qry) use ($data) {
                    return $qry->select([
                        'id',
                        'student_id',
                        'exam_id',
                        'days_absent'
                    ])->whereExamId($data['exam_id']);
                });
            })->get(['id', 'grade_id', 'user_id', 'class_no']);

        return $this->successResponse(["students" => $students, "cat_days" => $termSetting->cat_days]);
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
     * @throws Exception
     */
    public function upsert(UpdateAttendanceRequest $request, Grade $grade, TermSetting $termSetting): JsonResponse
    {
        $data = $request->validated();

        $model = match ($grade->level) {
            Level::PRIMARY => new PriCumulativeResult,
            Level::SECONDARY => new CumulativeResult,
            default => throw new Exception('Cannot set attendance for Alumni!')
        };

        $data["attendances"] = array_map(fn(array $att) => [
            ...$att,
            "exam_id" => $data['exam_id'],
            "total_days" => $termSetting->cat_days
        ], $data['attendances']);

        $model::upsert($data['attendances'], ['student_id', 'exam_id'], ['days_absent', 'total_days']);

        return $this->successResponse(msg: 'Attendances saved successfully!');
    }
}
