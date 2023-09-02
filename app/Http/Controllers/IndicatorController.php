<?php

namespace App\Http\Controllers;

use App\Models\LearningArea;
use App\Models\SubStrand;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = [
            'learningAreas' => LearningArea::all(),
            "subStrand"     => SubStrand::with('strand')->find($request->integer('sub-strand-id'), ['id', 'strand_id']),
        ];

        return view('pages.indicators.index', $data);
    }
}
