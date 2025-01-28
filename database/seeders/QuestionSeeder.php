<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = array(
            array('id' => '1','quiz_id' => '1','question' => 'ques-1','question_difficulty' => 'Medium','marks' => '1','type' => 'checkbox','total_options' => '4','created_at' => '2025-01-22 22:42:36','updated_at' => '2025-01-22 22:42:36'),
            array('id' => '2','quiz_id' => '2','question' => 'ques-1','question_difficulty' => 'Medium','marks' => '1','type' => 'checkbox','total_options' => '4','created_at' => '2025-01-22 23:19:27','updated_at' => '2025-01-22 23:19:27'),
            array('id' => '3','quiz_id' => '3','question' => 'ques-1','question_difficulty' => 'Medium','marks' => '2','type' => 'checkbox','total_options' => '4','created_at' => '2025-01-26 17:47:38','updated_at' => '2025-01-26 17:47:38'),
            array('id' => '4','quiz_id' => '3','question' => 'ques-2','question_difficulty' => 'Hard','marks' => '1','type' => 'long_text','total_options' => '4','created_at' => '2025-01-26 17:47:38','updated_at' => '2025-01-26 17:47:38'),
            array('id' => '5','quiz_id' => '3','question' => 'New Question','question_difficulty' => 'Easy','marks' => '3','type' => 'short_text','total_options' => NULL,'created_at' => '2025-01-26 17:47:38','updated_at' => '2025-01-26 17:47:38'),
            array('id' => '6','quiz_id' => '4','question' => 'Question-1','question_difficulty' => 'Easy','marks' => '1','type' => 'radio','total_options' => '4','created_at' => '2025-01-26 17:58:35','updated_at' => '2025-01-26 17:58:35'),
            array('id' => '7','quiz_id' => '4','question' => 'Question-2','question_difficulty' => 'Medium','marks' => '2','type' => 'checkbox','total_options' => '4','created_at' => '2025-01-26 17:58:35','updated_at' => '2025-01-26 17:58:35'),
            array('id' => '8','quiz_id' => '4','question' => 'Question 3','question_difficulty' => 'Hard','marks' => '3','type' => 'long_text','total_options' => NULL,'created_at' => '2025-01-26 17:58:35','updated_at' => '2025-01-26 17:58:35')
        );

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
