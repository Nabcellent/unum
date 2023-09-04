<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $data = [
            "grades" => Grade::secondary()->select('name')->distinct()->get()
        ];

        return view('pages.secondary.subjects.index', $data);
    }

    public function getSubjects(): JsonResponse
    {
        $subjects = Subject::with('grades')->get();

        return $this->successResponse($subjects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'      => 'required|string|unique:subjects',
            'classes.*' => 'string|filled|exists:grades,name'
        ]);

        $subject = Subject::create($data);

        if (isset($data['classes'])) {
            $gradeIds = Grade::whereIn('name', $data['classes'])->pluck('id');

            $subject->grades()->attach($gradeIds);
        }

        return $this->successResponse(msg: 'Subject saved!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject): JsonResponse
    {
        $data = $request->validate([
            'name'      => ['string', Rule::unique('subjects', 'name')->ignore($subject->id)],
            'classes.*' => 'string|filled|exists:grades,name'
        ]);

        $subject->update($data);

        $gradeIds = [];
        if (isset($data['classes'])) {
            $gradeIds = Grade::whereIn('name', $data['classes'])->pluck('id');
        }

        $subject->grades()->sync($gradeIds);

        return $this->successResponse(msg: 'Subject saved!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject): JsonResponse
    {
        $subject->delete();

        return $this->successResponse(msg: 'Subject Deleted!');
    }
}
