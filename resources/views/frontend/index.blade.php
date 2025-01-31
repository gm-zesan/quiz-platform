@extends('frontend.layouts.app')

@section('title', 'Home')



@section('content')

    @if (session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif

    <!-- banner area -->
    <div class="banner_area" style="background-image: url({{ asset('frontend/img/home_bg.png') }});">
        <div class="container">
            <div class="banner_content">
                <h4>QUIZ PLATFORM</h4>
                <h1>Quiz Platform is a platform where you can create and participate in quizzes.</h1>
                <a href="#" class="button mt_40">Get Started</a>
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
                        <div class="col-12">
                            <div class="custom-card">
                                <div class="card__left">
                                    <div class="card__icon">
                                        <div class="d-flex align-items-center mr-3" style="line-height: .8">
                                            <i class="fas fa-bolt"></i>
                                            <p class="q_count">{{ $quize->total_question }} Questions</p>
                                        </div>
                                        <p class="q_type">{{ $quize->is_public ? 'Public' : 'Private' }}</p>
                                    </div>
                                    <h3 class="card__title">{{ $quize->title }}</h3>
                                </div>
                                <p class="card__apply">
                                    <a class="card__link" href="{{ route('frontend.quizzes.participate', $quize->id) }}">Start Quiz <i class="fas fa-arrow-right"></i>
                                    </a>
                                </p>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

        {{-- pricing_area --}}
        <div class="getfeature_wrapper border_bottm section_padd" id="pricing">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 mt_30 text-center">
                        <div class="getfe_head">
                            <div class="title">
                                <h2>Pricing</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 mt_30">
                        <div class="tab-pane fade active show" id="nav-profile" role="tabpanel">
                            <div class="row justify-content-center">
                                <div class="col-lg-4 col-md-6 mt_30 text-center">
                                    <div class="getfe_item">
                                        <div class="getfe_txt">
                                            <h2>
                                                <span style="font-size: 25px;">{{ env('CURRENCY') == 'BDT' ? '৳' : '$' }}</span>
                                                0
                                            </h2>
                                            <h3>Free Plan (Active)</h3>
                                        </div>
                                        <div class="getfe_second">
                                            <ul>
                                                <li>Create only 5 quizes</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mt_30 text-center">
                                    <div class="getfe_item">
                                        <div class="getfe_txt">
                                            <h2>
                                                <span style="font-size: 25px;">{{ env('CURRENCY') == 'BDT' ? '৳' : '$' }}</span>
                                                {{ env('SUBSCRIPTION_PRICE') }} <span>/ Year</span>
                                            </h2>
                                            <h3>Premium Plan</h3>
                                        </div>
                                        <div class="getfe_second">
                                            <ul>
                                                <li>Create unlimited quizes</li>
                                            </ul>
                                            <form action="{{ route('sslcommerz.pay') }}" method="post">
                                                @csrf
                                                <button type="submit" class="button">Start Now</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
