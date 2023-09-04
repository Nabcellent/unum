<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLearningAreaRequest;
use App\Http\Requests\UpdateLearningAreaRequest;
use App\Models\Grade;
use App\Models\LearningArea;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class LearningAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $data = [
            "grades" => Grade::select('name')->distinct()->primary()->get()
        ];

        return view('pages.primary.learning-areas.index', $data);
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
    public function store(StoreLearningAreaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LearningArea $learningArea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LearningArea $learningArea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLearningAreaRequest $request, LearningArea $learningArea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LearningArea $learningArea)
    {
        //
    }
}
