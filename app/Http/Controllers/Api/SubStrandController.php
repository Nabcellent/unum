<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubStrandRequest;
use App\Http\Requests\UpdateSubStrandRequest;
use App\Models\Indicator;
use App\Models\SubStrand;
use Illuminate\Http\JsonResponse;

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
    public function store(StoreSubStrandRequest $request): JsonResponse
    {
        $data = $request->validated();

        SubStrand::create($data);

        return $this->successResponse(msg: 'Sub Strand Saved!');
    }

    public function update(UpdateSubStrandRequest $request, SubStrand $subStrand): JsonResponse
    {
        $data = $request->validated();

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
