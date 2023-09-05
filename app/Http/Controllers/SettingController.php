<?php

namespace App\Http\Controllers;

use App\Enums\Exam;
use App\Enums\Setting;
use App\Settings\TermSetting;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
//        dd(app(TermSetting::class)->report_exam_date->toDateString());
        $data = [
            "preferences" => app(TermSetting::class),
            "term"        => app(TermSetting::class),
            "system"      => app(TermSetting::class),
            "danger"      => app(TermSetting::class),

            "exams" => \App\Models\Exam::get()
        ];

        return view("pages.settings.index", $data);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @throws Exception
     */
    public function update(Request $request, Setting $setting): JsonResponse
    {
        $settingModel = match ($setting) {
            Setting::TERM => app(TermSetting::class),
            default => throw new Exception('Unexpected setting value')
        };

        if ($setting === Setting::TERM) {
            $settingModel->current = $request->integer('current');
            $settingModel->cat_days = $request->integer('days');
            $settingModel->current_exam = $request->enum('current_exam', Exam::class);
            $settingModel->report_exam_date = $request->date('report_exam_date');
            $settingModel->next_term_date = $request->date('next_term_date');
        }

        $settingModel->save();

        return response()->json(['status' => 'success', 'msg' => 'Settings Saved Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
