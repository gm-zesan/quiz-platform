<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Models\Quiz;
use App\Models\Participant;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Response;

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
                ->addColumn('description', function (Quiz $quiz) {
                    return Str::limit($quiz->description, 100);
                })
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

    public function showParticipants($quizId)
    {
        $quiz = Quiz::with(['questions', 'participants'])->findOrFail($quizId);

        // Get all responses grouped by question
        $questionsWithResponses = DB::table('questions')
        ->leftJoin('options', function ($join) {
            $join->on('questions.id', '=', 'options.question_id')
                 ->whereIn('questions.type', ['radio', 'checkbox']);
        })
        ->leftJoin('responses', 'questions.id', '=', 'responses.question_id')
        ->leftJoin('participants', 'responses.participant_id', '=', 'participants.id')
        ->select(
            'questions.id as question_id',
            'questions.question',
            'questions.type',
            'options.id as option_id',
            'options.option',
            'responses.answer',
            'responses.option_id as response_option_id'
        )
        ->where('questions.quiz_id', $quizId)
        ->orderBy('questions.id')
        ->get();

        
        $formattedData = [];

        foreach ($questionsWithResponses as $item) {
            $questionId = $item->question_id;
        
            if (!isset($formattedData[$questionId])) {
                $formattedData[$questionId] = [
                    'question' => $item->question,
                    'type' => $item->type,
                    'responses' => [],
                ];
            }
        
            if (in_array($item->type, ['radio', 'checkbox'])) {
                // For radio/checkbox questions, group responses by options
                if ($item->option_id) {
                    if (!isset($formattedData[$questionId]['responses'][$item->option])) {
                        $formattedData[$questionId]['responses'][$item->option] = 0;
                    }
                    // Count how many times this option was selected
                    if ($item->response_option_id == $item->option_id) {
                        $formattedData[$questionId]['responses'][$item->option]++;
                    }
                }
            } else {
                // For short/long questions, collect all answers
                if ($item->answer) {
                    $formattedData[$questionId]['responses'][] = $item->answer;
                }
            }
        }

        // dd($formattedData);

        return view('admin.quizzes.participants', [
            'quiz' => $quiz,
            'participants' => $quiz->participants,
            'formattedData' => $formattedData
        ]);
    }


    public function showSingleParticipant($quizId, $participantId)
    {
        $quiz = Quiz::with('questions.options')->findOrFail($quizId);
        $participant = Participant::findOrFail($participantId);
        $responses = Response::where('participant_id', $participantId)->get()->keyBy('question_id');
        
        return view('admin.quizzes.single-perticipant', compact('quiz', 'participant', 'responses'));
    }

    // Update the participant's result
    public function updateScore(Request $request, $quizId, $participantId)
    {
        $participant = Participant::where('id', $participantId)->where('quiz_id', $quizId)->first();
        $participant->update(['score' => $request->score]);

        return redirect()->route('admin.quizzes.single-participant', [$quizId, $participantId])
            ->with('success', 'Participant result updated successfully.');
    }



}
