<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Quiz;
use App\Models\Response;
use Illuminate\Http\Request;

class QuizPlatformController extends Controller
{
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
        return view('frontend.participate', compact('quiz'));
    }

    public function submit(Request $request, $id)
    {
        $quiz = Quiz::with('questions', 'questions.options')->findOrFail($id);

        $validated = $request->validate([
            'responses' => 'required|array',
            'responses.*' => 'nullable',
            'participant_name' => 'required_if:user,null|string|max:255',
            'participant_email' => 'required_if:user,null|email|max:255',
        ]);

        if (auth()->check()) {
            $existingParticipant = Participant::where('user_id', auth()->id())->first();
        }else{
            $existingParticipant = Participant::where('email', $request->input('participant_email'))
            ->first();
        }
        if ($existingParticipant) {
            $participant = $existingParticipant;
        } else {
            $participant = $quiz->participants()->create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'participant_name' => auth()->check() ? auth()->user()->name : $validated['participant_name'],
                'email' => auth()->check() ? auth()->user()->email : $validated['participant_email'],
                'submitted_at' => now(),
            ]);
        }
        

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
                        'participant_id' => $participant->id,
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
                    'participant_id' => $participant->id,
                    'question_id' => $question->id,
                    'option_id' => $responses
                ];
                $submittedResponses[$question->id] = $responses;
            } else {
                $responsesWithText[] = [
                    'participant_id' => $participant->id,
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
