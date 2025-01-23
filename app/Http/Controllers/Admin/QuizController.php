<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin.quizzes.edit', compact('quiz'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date|before:end_time',
            'end_time' => 'required|date|after:start_time',
            'timer' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.question_difficulty' => 'required|string',
            'questions.*.marks' => 'required|integer|min:1',
            'questions.*.options' => 'nullable|array|required_if:questions.*.type,radio,checkbox',
            'questions.*.options.*.id' => 'nullable|integer|exists:options,id',
            'questions.*.options.*.option' => 'nullable|string',
            'questions.*.options.*.is_correct' => 'nullable',
        ]);
        $quizData = $request->only(['title', 'description', 'start_time', 'end_time', 'timer']);
        $quiz->update($quizData);

        foreach ($validated['questions'] as $index => $questionData) {
            $question = $quiz->questions[$index];
    
            $question->question = $questionData['question'];
            $question->question_difficulty = $questionData['question_difficulty'];
            $question->marks = $questionData['marks'];
    
            if (in_array($question->type->value, ['radio', 'checkbox'])) {
                foreach ($questionData['options'] as $optionData) {
                    $isCorrect = isset($optionData['is_correct']) && $optionData['is_correct'] === 'on' ? 1 : 0;
                    $option = $question->options->find($optionData['id']);
                    if ($option) {
                        $option->update([
                            'option' => $optionData['option'],
                            'is_correct' => $isCorrect,
                        ]);
                    }
                }
            }
            $question->save();
        }

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
}
