<?php

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizzes = array(
            array('id' => '1','user_id' => '1','title' => 'Admin Quiz-1','description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.','is_public' => '1','start_time' => '2025-01-23 02:33:00','end_time' => '2025-01-31 04:42:00','timer' => '00:03:00','total_question' => '1','created_at' => '2025-01-22 22:42:36','updated_at' => '2025-01-22 22:42:36'),
            array('id' => '2','user_id' => '1','title' => 'Admin Quiz-2','description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.','is_public' => '0','start_time' => '2025-01-23 02:33:00','end_time' => '2025-01-31 04:42:00','timer' => '00:09:00','total_question' => '1','created_at' => '2025-01-22 23:19:27','updated_at' => '2025-01-22 23:19:27'),
            array('id' => '3','user_id' => '2','title' => 'User Quiz-1','description' => 'It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.','is_public' => '1','start_time' => '2025-01-25 23:36:00','end_time' => '2025-01-29 23:36:00','timer' => '01:30:00','total_question' => '3','created_at' => '2025-01-26 17:47:38','updated_at' => '2025-01-26 22:55:07'),
            array('id' => '4','user_id' => '2','title' => 'User Quiz-2','description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.','is_public' => '1','start_time' => '2025-01-25 23:36:00','end_time' => '2025-01-29 23:36:00','timer' => '00:02:00','total_question' => '3','created_at' => '2025-01-26 17:58:35','updated_at' => '2025-01-26 22:54:30')
        );

        foreach ($quizzes as $quiz) {
            Quiz::create($quiz);
        }
    }
}
