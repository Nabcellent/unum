<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $data = [
            "streams" => Stream::all()
        ];

        return view('pages.classes.index', $data);
    }
}
