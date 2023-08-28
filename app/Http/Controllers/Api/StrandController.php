<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Strand;
use App\Models\SubStrand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StrandController extends Controller
{
    public function getSubStrands(int $strandId): JsonResponse
    {
        $subStrands = SubStrand::whereStrandId($strandId)->withCount(['indicators'])->get();

        return $this->successResponse($subStrands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'             => 'required|string|unique:strands',
            'learning_area_id' => 'required|integer|exists:learning_areas,id'
        ]);

        Strand::create($data);

        return $this->successResponse(msg: 'Learning area saved!');
    }

    public function update(Request $request, Strand $strand): JsonResponse
    {
        $data = $request->validate([
            'name'             => ['string', Rule::unique('strands', 'name')->ignore($strand->id)],
            'learning_area_id' => 'integer|exists:learning_areas,id'
        ]);

        $strand->update($data);

        return $this->successResponse(msg: 'Strand saved!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Strand $strand): JsonResponse
    {
        $strand->delete();

        return $this->successResponse(msg: 'Strand Deleted!');
    }
}
