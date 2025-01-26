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
        auth()->user()->update([
            'created_quiz_count' => auth()->user()->created_quiz_count + 1,
        ]);

        return $quiz;
    }

    public function updateQuiz(Quiz $quiz, array $validated)
    {
        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'timer' => $validated['timer'],
        ]);

        foreach ($validated['questions'] as $index => $questionData) {
            $question = $quiz->questions[$index];

            $question->update([
                'question' => $questionData['question'],
                'question_difficulty' => $questionData['question_difficulty'],
                'marks' => $questionData['marks'],
            ]);

            if (in_array($question->type->value, ['radio', 'checkbox'])) {
                foreach ($questionData['options'] as $optionData) {
                    if($question->type->value === 'checkbox'){
                        $isCorrect = isset($optionData['is_correct']) && $optionData['is_correct'] === 'on' ? 1 : 0;
                    }
                    $option = $question->options->find($optionData['id']);
                    if ($option) {
                        $option->update([
                            'option' => $optionData['option'],
                            'is_correct' => $isCorrect ?? 0,
                        ]);
                        if($question->type->value === 'radio'){
                            $question->options()->where('id', $questionData['is_correct'])->update(['is_correct' => 1]);
                        }
                    }
                }
            }
        }
        return $quiz;
    }
}
