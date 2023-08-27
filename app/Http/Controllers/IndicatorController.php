<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIndicatorRequest;
use App\Http\Requests\UpdateIndicatorRequest;
use App\Models\Indicator;
use App\Models\LearningArea;
use App\Models\SubStrand;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $data = [
            'learningAreas' => LearningArea::all(),
            "subStrand" => SubStrand::with('strand')->find($request->integer('sub-strand-id'), ['id', 'strand_id']),
        ];

        return view('pages.indicators.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIndicatorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Indicator $indicator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Indicator $indicator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIndicatorRequest $request, Indicator $indicator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Indicator $indicator)
    {
        //
    }
}
