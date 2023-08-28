<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndicatorRequest;
use App\Http\Requests\UpdateIndicatorRequest;
use App\Models\Indicator;
use Illuminate\Http\JsonResponse;

class IndicatorController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIndicatorRequest $request): JsonResponse
    {
        $data = $request->validated();

        Indicator::create($data);

        return response()->json(['status' => true, 'msg' => 'Indicator Saved!']);
    }

    public function update(UpdateIndicatorRequest $request, Indicator $indicator): JsonResponse
    {
        $data = $request->validated();

        $indicator->update($data);

        return response()->json(['status' => true, 'msg' => 'Indicator Saved!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Indicator $indicator): JsonResponse
    {
        return response()->json(['status' => $indicator->delete(), 'msg' => 'Indicator Deleted!']);
    }
}
