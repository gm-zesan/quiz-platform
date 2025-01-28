<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Quiz;
use App\Models\Response;
use App\Services\ResponseService;
use Illuminate\Http\Request;

class QuizPlatformController extends Controller
{
    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }


    public function index()
    {
        $quizes = Quiz::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->get();
        return view('frontend.index', compact('quizes'));
    }

    public function participate($id)
    {
        $quiz = Quiz::with('questions', 'questions.options')->findOrFail($id);
        if (!$quiz->is_public && !auth()->check()) {
            return redirect()->route('login');
        }
        if(!auth()->check()){
            return view('frontend.participate', compact('quiz'));
        }else{
            $participant = $this->responseService->createparticipant($quiz);
            return view('frontend.quiz', compact('quiz', 'participant'));
        }
    }

    public function storePatricipant(Request $request, $id)
    {
        $quiz = Quiz::with('questions', 'questions.options')->findOrFail($id);

        $validated = $request->validate([
            'participant_name' => 'required|string|max:255',
            'participant_email' => 'required|email|max:255',
        ]);

        $participant = $this->responseService->createparticipant($quiz, $validated['participant_email'], $validated['participant_name']);

        return view('frontend.quiz', compact('quiz', 'participant'));
    }


    public function storeQuiz(Request $request, $id)
    {
        $quiz = Quiz::with('questions', 'questions.options')->findOrFail($id);
        $participantId = $request->participant_id;
        $validated = $request->validate([
            'responses' => 'nullable|array',
            'responses.*' => 'nullable',
        ]);
        $score = 0;
        $responsesWithOptions = [];
        $responsesWithText = [];
        $submittedResponses = [];
        foreach ($quiz->questions as $question) {
            $responses = $validated['responses'][$question->id] ?? null;

            if (is_array($responses)) {
                $correctOptions = $question->options->where('is_correct', true)->pluck('id')->toArray();
                $selectedOptions = $responses;
                
                if (empty(array_diff($correctOptions, $selectedOptions)) && count($correctOptions) == count($selectedOptions)) {
                    $score += $question->marks;
                }
                foreach ($selectedOptions as $optionId) {
                    $responsesWithOptions[] = [
                        'participant_id' => $participantId,
                        'question_id' => $question->id,
                        'option_id' => $optionId
                    ];
                    $submittedResponses[$question->id][] = $optionId;
                }
            } elseif ($question->type->value === 'radio') {
                $isCorrect = $question->options->firstWhere('id', $responses)?->is_correct;
                if ($isCorrect) {
                    $score += $question->marks;
                }
                $responsesWithOptions[] = [
                    'participant_id' => $participantId,
                    'question_id' => $question->id,
                    'option_id' => $responses
                ];
                $submittedResponses[$question->id] = $responses;
            } else {
                $responsesWithText[] = [
                    'participant_id' => $participantId,
                    'question_id' => $question->id,
                    'answer' => $responses
                ];
                $submittedResponses[$question->id] = $responses;
            }
        }

        if (!empty($responsesWithOptions)) {
            Response::insert($responsesWithOptions);
        }
        
        if (!empty($responsesWithText)) {
            Response::insert($responsesWithText);
        }
        return view('frontend.result', compact('quiz', 'score', 'submittedResponses'));
    }

}
