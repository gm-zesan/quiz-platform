<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuizRequest;
use App\Models\Quiz;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class QuizController extends Controller
{

    protected $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $quizzes = Quiz::get();
            return DataTables::of($quizzes)
                ->addIndexColumn()
                ->addColumn('start_time', function (Quiz $quiz) {
                    return $quiz->start_time->format('d F Y \a\t h:i A');
                })
                ->addColumn('end_time', function (Quiz $quiz) {
                    return $quiz->end_time->format('d F Y \a\t h:i A');
                })
                ->addColumn('user_id', function (Quiz $quiz) {
                    return $quiz->user->name;
                })
                ->addColumn('action-btn', function($row) {
                    return $row->id;
                })
                ->rawColumns(['action-btn'])
                ->make(true);
        }
        return view('admin.my-quizzes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $difficulties = ['Easy', 'Medium', 'Hard'];
        return view('admin.my-quizzes.create', compact('difficulties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuizRequest $request)
    {
        $this->quizService->storeQuiz($request->validated());
        return redirect()->route('quizzes.index')->with('success', 'Quiz created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['questions', 'user', 'participants']);
        return view('admin.my-quizzes.show', compact('quiz'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        $quiz->load(['questions', 'user', 'participants']);
        return view('admin.my-quizzes.edit', compact('quiz'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->questions()->each(function ($question) {
            $question->options()->delete();
        });
        $quiz->questions()->delete();
        $quiz->delete();
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz deleted successfully!');
    }
}
