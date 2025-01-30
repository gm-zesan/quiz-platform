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
            ->where('is_public', true)
            ->limit(5)
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

    public function startQuiz(Request $request, $quiz, $participant)
    {
        $quiz = Quiz::with('questions', 'questions.options')->findOrFail($quiz);
        $participant = Participant::findOrFail($participant);

        if($quiz->timer){
            if($participant->started_at){
                $timer = $participant->started_at->diffInSeconds(now());
                $quiz_time = explode(':', $quiz->timer);
                $quiz_time = $quiz_time[0] * 3600 + $quiz_time[1] * 60 + $quiz_time[2];
                $remaining_time = $quiz_time - $timer;
                if($remaining_time <= 0){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Your time is over',
                    ]);
                }
                $remaining_time_formatted = sprintf('%02d:%02d:%02d', floor($remaining_time / 3600), floor(($remaining_time % 3600) / 60), $remaining_time % 60);
                $quiz->timer = $remaining_time_formatted;
            }else{
                $participant->update(['started_at' => now()]);
            }
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $quiz,
        ]);
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
        // dd($request->all());
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
