<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStrandRequest;
use App\Http\Requests\UpdateStrandRequest;
use App\Models\LearningArea;
use App\Models\Strand;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $data = [
            'learningAreas' => LearningArea::all(),
            "learningAreaId" => $request->integer('learning-area-id')
        ];

        return view('pages.strands.index', $data);
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
    public function store(StoreStrandRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Strand $strand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Strand $strand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStrandRequest $request, Strand $strand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Strand $strand)
    {
        //
    }
}
