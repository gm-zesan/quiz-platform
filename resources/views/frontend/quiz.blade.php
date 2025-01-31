<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participate in Quiz</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        body {
            line-height: 1.5;
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            color: #000;
            font-weight: 400;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
        }

        .form-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #343a40;
            text-align: center;
        }

        .form-label {
            font-weight: bold;
            color: #495057;
        }

        .form-input,
        .form-select {
            border: 1px solid #ced4da;
            border-radius: 0px;
            width: 100%;
            margin-top: 5px;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: #000000;
            box-shadow: none;
            outline: none;
        }

        .form-check-input:checked {
            background-color: #000000;
            border-color: #000000;
            box-shadow: none;
        }

        .btn-custom {
            display: inline-block;
            font-weight: 700;
            color: #fff;
            background-color: #000;
            text-align: center;
            padding: 10px;
            text-transform: uppercase;
            font-size: 16px;
            border: 2px solid transparent;
            border-radius: 0px;
            transition: .3s all ease;
        }

        .btn-custom:hover {
            border: 2px solid #000000;
            background-color: #ffffff;
            color: #000000;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="form-container">
                    <div class="d-flex justify-content-between align-items-center my-3">
                        <a href="{{ route('frontend.home') }}" class="btn btn-link p-0">&lt; Back to Home</a>
                        @if($quiz->timer)
                            <div id="timer" class="text-danger fw-bold text-end">
                                {{ $quiz->timer }} munites
                            </div>
                        @endif
                    </div>
                    
                    <h2>{{ $quiz->title }}</h2>
                    <p>{{ $quiz->description }}</p>

                    <div>
                        <button type="button" class="btn btn-custom w-100" id="start-quiz">Start Quiz</button>
                    </div>

                    <div class="form-container" id="quiz-container">
                        @if($quiz->timer)
                            <div id="timer" class="text-danger fw-bold text-end">
                                Time Remaining: <span id="time">00:00:00</span>
                            </div>
                        @endif
                        <div class="quiz-container">
                            <form id="quizForm" method="POST" action="{{ route('frontend.quizzes.submit', $quiz->id) }}">
                                @csrf
                                <input type="hidden" name="participant_id" value="{{ $participant->id }}">
                                <div id="quiz-questions"></div>
                                <button type="submit" class="btn btn-custom w-100">Submit</button>
                                <a href="{{ route('frontend.home') }}" class="btn btn-link w-100 mt-3">Back to Home</a>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        
        $('#quiz-container').hide();

        $(document).ready(function() {
            $('#start-quiz').click(function() {
                // Start the quiz and load questions
                $.get('{{ route('frontend.quizzes.start', [$quiz->id, $participant->id]) }}', function(response) {
                    if (response.status === 'success') {
                        $('#quiz-container').show();
                        const quiz = response.data;
                        let questionsHtml = '';

                        // Build the questions HTML
                        quiz.questions.forEach((question, index) => {
                            questionsHtml += `
                                <div class="question mb-3">
                                    <label class="form-label">${index + 1}. ${question.question}</label>`;

                            if (question.type === 'radio' || question.type === 'checkbox') {
                                question.options.forEach(option => {
                                    questionsHtml += `
                                        <div class="form-check">
                                            <input class="form-check-input" id="option-${option.id}" 
                                                type="${question.type}" 
                                                name="responses[${question.id}]${question.type === 'checkbox' ? '[]' : ''}" 
                                                value="${option.id}">
                                            <label class="form-check-label" for="option-${option.id}">
                                                ${option.option}
                                            </label>
                                        </div>`;
                                });
                            } else {
                                questionsHtml += `
                                    <textarea class="form-control form-input" rows="4" 
                                        name="responses[${question.id}]" 
                                        placeholder="Type your answer..."></textarea>`;
                            }

                            questionsHtml += `</div>`;
                        });

                        // Insert questions into the container
                        $('#quiz-questions').html(questionsHtml);
                        $('#start-quiz').hide();

                        // Start the timer if exists
                        if(quiz.timer) {
                            const timerParts = quiz.timer.split(':');
                            const hours = parseInt(timerParts[0]);
                            const minutes = parseInt(timerParts[1]);
                            const seconds = parseInt(timerParts[2]);
                            let timeLeft = (hours * 3600) + (minutes * 60) + seconds;

                            const timerInterval = setInterval(updateTimer, 1000);

                            function updateTimer() {
                                const hoursLeft = Math.floor(timeLeft / 3600);
                                const minutesLeft = Math.floor((timeLeft % 3600) / 60);
                                let secondsLeft = timeLeft % 60;

                                const formattedHours = String(hoursLeft).padStart(2, '0');
                                const formattedMinutes = String(minutesLeft).padStart(2, '0');
                                const formattedSeconds = String(secondsLeft).padStart(2, '0');

                                $('#time').text(`${formattedHours}:${formattedMinutes}:${formattedSeconds}`);

                                if (timeLeft <= 0) {
                                    clearInterval(timerInterval);
                                    $('#quizForm').submit();
                                }
                                timeLeft--;
                            }

                            updateTimer();
                        }
                    }else if(response.status === 'error'){
                        $('#quiz-container').html(`
                            <div class="alert alert-danger">
                                ${response.message}
                            </div>
                        `);
                        $('#quiz-container').addClass('mt-5');
                        $('#quiz-container').show();
                        console.log(response);
                    }else{
                        alert('Something went wrong');
                    }
                });
            });
        });
    </script>
</body>

</html>
