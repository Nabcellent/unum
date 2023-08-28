<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\LearningArea;
use App\Models\Strand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LearningAreaController extends Controller
{
    public function getLearningAreas(): JsonResponse
    {
        $learningAreas = LearningArea::with('grades')->withCount(['strands'])->get();

        return $this->successResponse($learningAreas);
    }

    public function getStrands(int $learningAreaId): JsonResponse
    {
        $strands = Strand::whereLearningAreaId($learningAreaId)->with('learningArea')->withCount(['subStrands'])->get();

        return $this->successResponse($strands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'      => 'required|string|unique:learning_areas',
            'classes.*' => 'string|filled|exists:grades,name'
        ]);

        $subject = LearningArea::create($data);

        if(isset($data['classes'])) {
            $gradeIds = Grade::whereIn('name', $data['classes'])->pluck('id');

            $subject->grades()->attach($gradeIds);
        }

        return response()->json(['status' => true, 'msg' => 'Learning area saved!']);
    }

    public function update(Request $request, LearningArea $learningArea): JsonResponse
    {
        $data = $request->validate([
            'name'      => ['string', Rule::unique('subjects', 'name')->ignore($learningArea->id)],
            'classes.*' => 'string|filled|exists:grades,name'
        ]);

        $learningArea->update($data);

        $gradeIds = [];
        if(isset($data['classes'])) {
            $gradeIds = Grade::whereIn('name', $data['classes'])->pluck('id');
        }

        $learningArea->grades()->sync($gradeIds);

        return response()->json(['status' => true, 'msg' => 'Learning area saved!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LearningArea $learningArea): JsonResponse
    {
        return response()->json(['status' => $learningArea->delete(), 'msg' => 'Learning area Deleted!']);
    }
}
