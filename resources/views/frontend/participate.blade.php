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
                    <h2>{{ $quiz->title }}</h2>
                    <p>Enter Your Name and Email</p>
                    <form method="POST" action="{{ route('frontend.participant.submit', $quiz->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="participant_name" class="form-label">Name</label>
                            <input type="text" name="participant_name" id="participant_name"
                                class="form-control form-input" placeholder="Enter your name" required>
                        </div>

                        <div class="mb-3">
                            <label for="participant_email" class="form-label">Email</label>
                            <input type="email" name="participant_email" id="participant_email"
                                class="form-control form-input" placeholder="Enter your email" required>
                        </div>

                        <button type="submit" class="btn btn-custom w-100">Submit</button>
                        <a href="{{ route('frontend.home') }}" class="btn btn-link w-100 mt-3">Back to Home</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
