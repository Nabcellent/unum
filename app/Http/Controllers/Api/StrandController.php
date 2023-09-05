<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStrandRequest;
use App\Http\Requests\UpdateStrandRequest;
use App\Models\Strand;
use App\Models\SubStrand;
use Illuminate\Http\JsonResponse;

class StrandController extends Controller
{
    public function getSubStrands(int $strandId): JsonResponse
    {
        $subStrands = SubStrand::whereStrandId($strandId)->withCount(['indicators'])
            ->latest('id')->get();

        return $this->successResponse($subStrands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStrandRequest $request): JsonResponse
    {
        $data = $request->validated();

        Strand::create($data);

        return $this->successResponse(msg: 'Learning area saved!');
    }

    public function update(UpdateStrandRequest $request, Strand $strand): JsonResponse
    {
        $data = $request->validated();

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
