@extends('frontend.layouts.app')

@section('title', 'Home')



@section('content')
<!-- banner area -->
<div class="banner_area" style="background-image: url({{ asset('frontend/img/home_bg.jpg') }});">
    <div class="container">
        <div class="banner_content">
            <h4>QUIZ PLATFORM</h4>
            <h1>Quiz Platform is a platform where you can create and participate in quizzes.</h1>
            <a href="#" class="button mt_40">Register Now</a>
        </div>
    </div>
</div>

<main class="overflow-hidden">
   
    <div class="prize_area" id="prize">
        <div class="container">
            <div class="title">
                <h2>Features</h2>
            </div>
            <div class="row mt_40">
                
                <div class="col-lg-4 col-sm-4 col-6 mt_40">
                    <div class="prize_box">
                        <h2>Participate</h2>
                        <p>Participate in quizzes created by other users.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 col-6 mt_40">
                    <div class="prize_box">
                        <h2>Create Quizzes</h2>
                        <p>Create quizzes and share them with your friends.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 col-6 mt_40">
                    <div class="prize_box">
                        <h2>Leaderboard</h2>
                        <p>View the leaderboard to see the top performers.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <!-- Detailed_area -->
     <div class="launched_area" id="brief" style="background-image: url({{ asset('frontend/img/pattern.png') }});">
        <div class="container">
            <h2>All Quizzes</h2>
            <div class="row">
                @foreach ($quizes as $quize)
                    <div class="col-md-6 col-12">
                        <div class="custom-card">
                            <div class="card__icon">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-bolt"></i> 
                                    <p class="q_count">{{ $quize->total_question }} Questions</p>
                                </div>
                                <p class="q_type">{{ $quize->is_public ? 'Public' : 'Private' }}</p>
                            </div>
                            <h3 class="card__title">{{ $quize->title }}</h3>
                            <p class="card__apply">
                                <a class="card__link" href="{{ route('frontend.public-quizzes.participate', $quize->id) }}">Start Quiz <i class="fas fa-arrow-right"></i></a>
                            </p>
                        </div>
                    </div>
                @endforeach
                
            </div>
        </div>
    </div>
</main>
@endsection