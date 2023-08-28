<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Indicator;
use App\Models\SubStrand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubStrandController extends Controller
{
    public function getIndicators(int $subStrandId): JsonResponse
    {
        $indicators = Indicator::whereSubStrandId($subStrandId)->get();

        return $this->successResponse($indicators);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'      => 'required|string|unique:sub_strands',
            'strand_id' => 'required|integer|exists:strands,id'
        ]);

        SubStrand::create($data);

        return $this->successResponse(msg: 'Sub Strand Saved!');
    }

    public function update(Request $request, SubStrand $subStrand): JsonResponse
    {
        $data = $request->validate([
            'name'      => ['string', Rule::unique('sub_strands', 'name')->ignore($subStrand->id)],
            'strand_id' => 'integer|exists:strands,id'
        ]);

        $subStrand->update($data);

        return $this->successResponse(msg: 'Sub Strand saved!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubStrand $subStrand): JsonResponse
    {
        $subStrand->delete();

        return $this->successResponse(msg: 'Sub Strand Deleted!');
    }
}
