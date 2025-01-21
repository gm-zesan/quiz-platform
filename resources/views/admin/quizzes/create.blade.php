@extends('admin.app')

@section('title', 'Create Quiz')

@push('custom-style')
    <style>
        .quiz-save-button{
            background-color: #23b7e9;
            color: #fff;
            border: none;
            padding: 8px 0px;
            width: 100%;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .quiz-save-button:hover{
            background-color: #1a8ac9;
            color: #fff;
        }
        .quiz-save-button:focus{
            outline: none;
            box-shadow: none;
        }

    </style>
@endpush

@section('content')
    <div class="container-fluid my-3">
        <form id="quiz-form">
            @csrf
            <div id="quiz-details">
                <div class="row g-4">
                    <div class="col-md-8 col-12">
                        <div class="card table-card">
                            <div class="card-header table-header">
                                <div class="table-title">Quiz Details</div>
                            </div>
                            <div class="card-body custom-form">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="title" class="form-label custom-label">Quiz Title</label>
                                        <input type="text" class="form-control custom-input" name="title"
                                            id="title" placeholder="Enter quiz title" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="description" class="form-label custom-label">Description</label>
                                        <textarea class="form-control custom-input" name="description" id="description" rows="5" required></textarea>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="is_public" class="form-label custom-label">Is Public?</label>
                                        <select name="is_public" id="is_public" class="form-control custom-input">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="start_time" class="form-label custom-label">Start Time</label>
                                        <input type="datetime-local" class="form-control custom-input" name="start_time"
                                            id="start_time" required>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="end_time" class="form-label custom-label">End Time</label>
                                        <input type="datetime-local" class="form-control custom-input" name="end_time"
                                            id="end_time" required>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="timer" class="form-label custom-label">Timer (in minutes)</label>
                                        <input type="number" class="form-control custom-input" name="timer"
                                            id="timer" required>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="total_question" class="form-label custom-label">Total Questions</label>
                                        <input type="number" class="form-control custom-input" name="total_question"
                                            id="total_question" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="card table-card">
                            <div class="card-header table-header">
                                <div class="table-title">Actions</div>
                            </div>
                            <div class="card-body custom-form">
                                <button type="button" id="next-button" class="btn submit-button">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="question-details" style="display: none;">
                <div id="questions-container"></div>
                <button type="button" id="save-quiz-button" class="btn quiz-save-button">Save Quiz</button>
            </div>
        </form>
    </div>
@endsection

@push('custom-scripts')
    <script>
        const difficulties = @json($difficulties);
    </script>

    <script>
        document.getElementById('next-button').addEventListener('click', function() {
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            const isPublic = document.getElementById('is_public').value;
            const startTime = document.getElementById('start_time').value;
            const endTime = document.getElementById('end_time').value;
            const timer = document.getElementById('timer').value;
            const totalQuestions = document.getElementById('total_question').value;
            if (!title || !description || !startTime || !endTime || !timer || !totalQuestions) {
                alert('Please fill out all fields before proceeding.');
                return;
            }
            document.getElementById('quiz-details').style.display = 'none';
            document.getElementById('question-details').style.display = 'block';

            const questionsContainer = document.getElementById('questions-container');
            questionsContainer.innerHTML = '';

            for (let i = 0; i < totalQuestions; i++) {
                const questionHtml = `
                <div class="card table-card mb-3">
                    <div class="card-header table-header">
                        <div class="table-title">Question ${i + 1}</div>
                    </div>
                    <div class="card-body custom-form">
                        <div class="row">
                            <div class="col-12">
                                <label for="questions[${i}][question]" class="form-label custom-label">Question</label>
                                <textarea name="questions[${i}][question]" class="form-control custom-input" required></textarea>
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="questions[${i}][question_difficulty]" class="form-label custom-label">Difficulty</label>
                                <select name="questions[${i}][question_difficulty]" class="form-control custom-input" required>
                                    ${difficulties.map(difficulty => `<option value="${difficulty}">${difficulty}</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="questions[${i}][marks]" class="form-label custom-label">Marks</label>
                                <input type="number" name="questions[${i}][marks]" class="form-control custom-input" required>
                            </div>
                            <div class="col-md-4 col-12">
                                <label for="questions[${i}][type]" class="form-label custom-label">Type</label>
                                <select name="questions[${i}][type]" class="form-control custom-input question-type-select" data-question-index="${i}" required>
                                    <option value="short_text">Short Text</option>
                                    <option value="long_text">Long Text</option>
                                    <option value="radio">Radio</option>
                                    <option value="checkbox">Checkbox</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="row" id="options-container-${i}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                questionsContainer.innerHTML += questionHtml;
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('question-type-select')) {
                const questionIndex = e.target.getAttribute('data-question-index');
                const optionsContainer = document.getElementById(`options-container-${questionIndex}`);
                optionsContainer.innerHTML = '';

                if (e.target.value === 'radio' || e.target.value === 'checkbox') {
                    optionsContainer.innerHTML = `
                    <div class="col-md-4 col-12">
                        <label for="questions[${questionIndex}][total_options]" class="form-label custom-label">Total Options</label>
                        <input type="number" name="questions[${questionIndex}][total_options]" class="form-control custom-input total-options-input" data-question-index="${questionIndex}" required>
                    </div>
                `;
                }
            }
        });

        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('total-options-input')) {
                const questionIndex = e.target.getAttribute('data-question-index');
                const totalOptions = e.target.value;
                const optionsContainer = document.getElementById(`options-container-${questionIndex}`);
                const optionsHtml = [];

                for (let i = 0; i < totalOptions; i++) {
                    optionsHtml.push(`
                    <div class="col-lg-3 col-md-4 col-12">
                        <label for="questions[${questionIndex}][options][${i}]" class="form-label custom-label">Option ${i + 1}</label>
                        <input type="text" name="questions[${questionIndex}][options][${i}][option]" class="form-control custom-input" required>
                    </div>
                `);
                }
                optionsContainer.innerHTML = optionsHtml.join('');
            }
        });
    </script>
@endpush
