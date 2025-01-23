<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;

class QuizService
{
    public function storeQuiz(array $data)
    {
        $quiz = Quiz::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'description' => $data['description'],
            'is_public' => $data['is_public'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'timer' => $data['timer'],
            'total_question' => $data['total_question'],
        ]);

        foreach ($data['questions'] as $questionData) {
            $totalOptions = isset($questionData['total_options']) ? $questionData['total_options'] : null;
            $question = Question::create([
                'quiz_id' => $quiz->id,
                'question' => $questionData['question'],
                'question_difficulty' => $questionData['question_difficulty'],
                'marks' => $questionData['marks'],
                'type' => $questionData['type'],
                'total_options' => $totalOptions,
            ]);

            if (in_array($questionData['type'], ['radio', 'checkbox'])) {
                foreach ($questionData['options'] as $optionData) {
                    Option::create([
                        'question_id' => $question->id,
                        'option' => $optionData['option'],
                        'is_correct' => false,
                    ]);
                }

                $correctOptionIndexes = $questionData['correct_option'] ?? [];
                foreach ($correctOptionIndexes as $index) {
                    $question->options[$index]->update([
                        'is_correct' => true,
                    ]);
                }
            }
        }

        return $quiz;
    }
}
