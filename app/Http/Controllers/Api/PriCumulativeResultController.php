<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PriCumulativeResult;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriCumulativeResultController extends Controller
{
    public function upsert(Request $request): JsonResponse
    {
        $data = $request->validate([
            "results.*.id"           => "integer",
            "results.*.behaviour"    => "required|array",
            "results.*.student_id"   => "required|exists:students,id",
            "results.*.conduct"      => "nullable|in:A,B,C,D,E",
            "results.*.sports_grade" => "nullable|in:A,B,C,D,E",
            "exam_id"                => "required|exists:exams,id",
        ]);

        $data['results'] = array_map(fn($res) => [
            ...$res,
            "exam_id"      => $data['exam_id'],
            "behaviour"    => json_encode($res['behaviour']),
            "conduct"      => $res['conduct'] ?? null,
            "sports_grade" => $res['sports_grade'] ?? null,
        ], $data['results']);

        PriCumulativeResult::upsert($data['results'], ["student_id", "exam_id"], [
            'behaviour',
            'conduct',
            'sports_grade'
        ]);

        return $this->successResponse(msg: 'Results saved successfully!');
    }
}
