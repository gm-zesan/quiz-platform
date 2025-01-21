<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $quizzes = Quiz::get();
            return DataTables::of($quizzes)
                ->addIndexColumn()
                ->addColumn('user_id', function (Quiz $quiz) {
                    return $quiz->user->name;
                })
                ->addColumn('action-btn', function($row) {
                    return $row->id;
                })
                ->rawColumns(['action-btn'])
                ->make(true);
        }
        return view('admin.quizzes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $difficulties = ['Easy', 'Medium', 'Hard'];
        return view('admin.quizzes.create', compact('difficulties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
    }


    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['questions', 'user', 'participants']);
        return view('admin.quizzes.show', compact('quiz'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        $quiz->load(['questions', 'user', 'participants']);
        return view('admin.quizzes.edit', compact('quiz'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $quiz->update($validated);

        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz deleted successfully!');
    }
}
