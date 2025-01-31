<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Question;


class AdminDashboardController extends Controller
{
    public function index(){
        $course_count = 1;
        $student_count = 1;
        $teacher_count = 1;
        $total_participants = Participant::join('quizzes', 'participants.quiz_id', '=', 'quizzes.id')->where('quizzes.user_id', auth()->user()->id)->count();
        
        $average_score = 50;
        
        return view('admin.home.index', compact('course_count', 'student_count', 'teacher_count', 'total_participants', 'average_score'));
    }
}
