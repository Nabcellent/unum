<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubStrandRequest;
use App\Http\Requests\UpdateSubStrandRequest;
use App\Models\LearningArea;
use App\Models\Strand;
use App\Models\SubStrand;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SubStrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $data = [
            'learningAreas' => LearningArea::all(),
            "strand" => Strand::find($request->integer('strand-id'), ['id', 'learning_area_id']),
        ];

        return view('pages.sub-strands.index', $data);
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
    public function store(StoreSubStrandRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SubStrand $subStrand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubStrand $subStrand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubStrandRequest $request, SubStrand $subStrand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubStrand $subStrand)
    {
        //
    }
}
