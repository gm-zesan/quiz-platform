<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuizRequest;
use App\Http\Requests\UpdateQuizRequest;
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
            if (auth()->user()->hasRole('admin')){
                $quizzes = Quiz::get();
            } else {
                $quizzes = Quiz::where('user_id', auth()->id())->get();
            }
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
    public function store(QuizRequest $request)
    {
        $this->quizService->storeQuiz($request->validated());
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz created successfully!');
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
        $hours = null;
        $minutes = null;
        if (!empty($quiz->timer)) {
            [$hours, $minutes] = explode(':', $quiz->timer);
        }
        return view('admin.quizzes.edit', compact('quiz', 'hours', 'minutes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuizRequest $request, Quiz $quiz)
    {
        $validated = $request->validated();
        $this->quizService->updateQuiz($quiz, $validated);
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz updated successfully!');
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

    public function showParticipants(Quiz $quiz)
    {
        $participants = $quiz->participants()->get();
        return view('admin.quizzes.participants', compact('quiz', 'participants'));
    }

    // public function showSharedQuiz($id)
    // {
    //     $quiz = Quiz::with(['questions', 'questions.options'])->findOrFail($id);

    //     if (!$quiz->is_public) {
    //         abort(403, 'This quiz is not available for sharing.');
    //     }

    //     return view('quizzes.shared', compact('quiz'));
    // }

    


}
