<?php

namespace App\Http\Controllers;

use App\Models\LearningArea;
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

        return view('pages.primary.strands.index', $data);
    }
}
