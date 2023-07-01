<?php

namespace App\Http\Controllers;

class SummaryController extends Controller
{
    public function index()
    {
        return view('pages.summaries.index');
    }
}
