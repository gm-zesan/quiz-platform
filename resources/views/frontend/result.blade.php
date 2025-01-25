<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
    <div class="container mt-5">
        <h2>Quiz Results: {{ $quiz->title }}</h2>
        <div class="alert alert-info">
            <p>Your Score: {{ $score }} / {{ $quiz->questions->sum('marks') }} (Based on radio and checkbox questions only)</p>
        </div>

        <div class="card my-4">
            <div class="card-body">
                <h3>Quiz Review</h3>
                <ul class="list-group">
                    @foreach ($quiz->questions as $question)
                        <li class="list-group-item">
                            <strong>Question:</strong> {{ $question->question }}
                            <br>
                            @if ($question->type->value === 'radio' || $question->type->value === 'checkbox')
                                <ul>
                                    @foreach ($question->options as $index => $option)
                                        @php
                                            $isUserCorrect = false;
                                            $userResponse = $responses[$index] ?? null;
                                            if (is_array($userResponse)) {
                                                $isUserCorrect = in_array($option->id, $userResponse);
                                            } elseif ($userResponse !== null) {
                                                $isUserCorrect = $userResponse == $option->id;
                                            }
                                        @endphp
                                        <li
                                            class="
                                            @if ($option->is_correct) text-success
                                            @elseif ($isUserCorrect)
                                                text-danger @endif
                                         ">
                                            {{ $option->option }}

                                            <span>
                                                @if ($responses[$index] ?? null === $option->id)
                                                    @if ($option->is_correct)
                                                    ✔@else❌
                                                    @endif
                                                @endif
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Your response has been saved.</span>
                                <textarea class="form-control" rows="4" readonly>{{ $question->answer }}</textarea>
                            @endif

                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <a href="{{ route('frontend.home') }}" class="btn btn-custom">Back to Home</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
