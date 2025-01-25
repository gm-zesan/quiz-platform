<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Question;
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
        if (!$quiz->is_public) {
            return redirect()->route('login');
        }
        return view('frontend.participate', compact('quiz'));
    }

    public function submit(Request $request, $id)
    {
        $quiz = Quiz::with('questions', 'questions.options')->findOrFail($id);

        if (now()->greaterThan($quiz->end_time)) {
            return redirect()->route('frontend.home')->withErrors(['quiz' => 'The quiz time has expired.']);
        }

        $validated = $request->validate([
            'responses' => 'required|array',
            'responses.*' => 'nullable',
            'participant_name' => 'required_unless:auth,1',
            'participant_email' => 'required_unless:auth,1|email',
        ]);
        $existingParticipant = Participant::where('participant_email', $request->input('participant_email'))
            ->first();

        if ($existingParticipant) {
            $participant = $existingParticipant;
        } else {
            $participant = Participant::create([
                'quiz_id' => $quiz->id,
                'user_id' => auth()->id(),
                'participant_name' => auth()->check() ? auth()->user()->name : $request->input('participant_name'),
                'participant_email' => auth()->check() ? auth()->user()->email : $request->input('participant_email'),
                'submitted_at' => now(),
            ]);
        }
        

        $score = 0;
        foreach ($quiz->questions as $question) {
            $responses = $validated['responses'][$question->id] ?? null;

            if (is_array($responses)) {
                $correctOptions = $question->options->where('is_correct', true)->pluck('id')->toArray();
                $selectedOptions = $responses;
                
                if (empty(array_diff($correctOptions, $selectedOptions)) && count($correctOptions) == count($selectedOptions)) {
                    $score += $question->marks;
                }
                foreach ($selectedOptions as $optionId) {
                    Response::create([
                        'participant_id' => $participant->id,
                        'question_id' => $question->id,
                        'option_id' => $optionId,
                    ]);
                }
            } elseif ($question->type === 'radio') {
                $isCorrect = $question->options->firstWhere('id', $responses)?->is_correct;
                if ($isCorrect) {
                    $score += $question->marks;
                }
                Response::create([
                    'participant_id' => $participant->id,
                    'question_id' => $question->id,
                    'option_id' => $responses,
                ]);
            } else {
                Response::create([
                    'participant_id' => $participant->id,
                    'question_id' => $question->id,
                    'answer' => $responses,
                ]);
            }
        }
        return view('frontend.result', compact('quiz', 'responses', 'score'));
    }

}
