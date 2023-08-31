<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertPriResultRequest;
use App\Models\Grade;
use App\Models\PriCumulativeResult;
use App\Models\PriResult;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PriResultController extends Controller
{
    /**
     * @throws Throwable
     */
    public function upsertPerSubStrand(UpsertPriResultRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['marks'] = array_map(fn($mark) => [
            ...$mark,
            "exam_id"          => $data['exam_id'],
            "sub_strand_id" => $data['sub_strand_id'],
        ], $data['marks']);

        PriResult::upsert($data['marks'], [], ['mark']);

        $grade = Grade::find($data['grade_id']);

        PriResult::updateRankingAndQuarters($data['exam_id'], $data['sub_strand_id'], $grade->name);
        PriCumulativeResult::updatePassesRankingAndQuarters($data['exam_id']);

        return $this->successResponse(msg: 'Results saved successfully!');
    }

    /**
     * @throws Throwable
     */
    public function upsertPerStudent(Request $request, Student $student): JsonResponse
    {
        $data = $request->validate([
            "results.*.mark"                  => "nullable|integer|max:99",
            "results.*.sub_strand_id"      => "required|exists:sub_strands,id",
            "exam_id"                         => "required|exists:exams,id",
            "cumulative_result.conduct"       => "nullable|in:A,B,C,D,E",
            "cumulative_result.sports_grade"  => "nullable|in:A,B,C,D,E",
            "cumulative_result.days_attended" => "nullable|integer",
            "cumulative_result.total_days"    => "nullable|integer",
        ]);

        $data['results'] = array_map(fn($mark) => [
            "learning_area_id" => $mark['learning_area_id'],
            "mark"             => $mark['mark'] ?? null,
            "student_id"       => $student->id,
            "exam_id"          => $data['exam_id'],
        ], $data['results']);

        PriResult::upsert($data['results'], [], ['mark']);

        $passes = $student->primaryResults()->whereExamId($data['exam_id'])->where('mark', '>=', 40)->count();
            $cumulativeResult = $student->primaryCumulativeResults()->firstWhere(['exam_id' => $data['exam_id']]);

        if ($cumulativeResult->passes != $passes) {
            $data['cumulative_result']['passes'] = $passes;
        }

        $cumulativeResult->update($data['cumulative_result']);

        foreach ($data['results'] as $result) {
            PriResult::updateRankingAndQuarters($data['exam_id'], $result['learning_area_id'], $student->grade->name);
        }

        PriCumulativeResult::updateRankingAndQuarters($data['exam_id']);

        return $this->successResponse(msg: 'Results saved successfully!');
    }
}
