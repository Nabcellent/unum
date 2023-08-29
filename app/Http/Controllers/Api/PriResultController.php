<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertPriResultRequest;
use App\Models\Grade;
use App\Models\PriResult;
use Illuminate\Http\JsonResponse;
use Throwable;

class PriResultController extends Controller
{
    /**
     * @throws Throwable
     */
    public function upsert(UpsertPriResultRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['marks'] = array_map(fn($mark) => [
            ...$mark,
            "exam_id"    => $data['exam_id'],
            "learning_area_id" => $data['learning_area_id'],
        ], $data['marks']);

        PriResult::upsert($data['marks'], [], ['mark']);

        $grade = Grade::find($data['grade_id']);

        PriResult::updateRankingAndQuarters($data['exam_id'], $data['learning_area_id'], $grade->name);
//        CumulativeResult::updatePassesRankingAndQuarters($data['exam_id']);

        return $this->successResponse(msg:'Results saved successfully!');
    }
}
