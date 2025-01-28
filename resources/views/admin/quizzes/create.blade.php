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
        .multi-select2 + .select2-container .select2-search__field::placeholder {
            font-size: 13px;
        }
        .multiple-select2 + .select2-container .select2-search__field::placeholder {
            font-size: 13px;
        }
        .multi-select2 + .select2-container .select2-selection--multiple {
            min-height: 28px;
            height: auto !important;
        }
        .multiple-select2 + .select2-container .select2-selection--multiple {
            min-height: 28px;
            height: auto !important;
        }
        .multi-select2
            + .select2-container--default
            .select2-selection--multiple
            .select2-selection__choice {
            margin-top: 0px;
            max-height: 100%;
            min-height: 22px;
            line-height: 1;
        }
        .multi-select2
            + .select2-container
            .select2-search--inline
            .select2-search__field {
            height: 19px;
        }

    </style>
@endpush

@section('content')
    <div class="container-fluid my-3">
        <form id="quiz-form" action="{{ route('admin.quizzes.store') }}" method="POST">
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
                                        <textarea class="form-control custom-textarea" name="description" id="description" rows="5" required></textarea>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="is_public" class="form-label custom-label">Is Public?</label>
                                        <select name="is_public" id="is_public" class="form-select custom-select single-select2">
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
                                        <label for="timer" class="form-label custom-label">Timer</label>
                                        <div class="d-flex gap-2 align-items-center">
                                            <select name="hours" id="hours" class="form-select custom-input single-select2">
                                                <option value="" disabled selected>Hours</option>
                                                @for ($i = 0; $i < 24; $i++)
                                                    <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                                @endfor
                                            </select>
                                        
                                            <select name="minutes" id="minutes" class="form-select custom-input single-select2">
                                                <option value="" disabled selected>Minutes</option>
                                                @for ($i = 0; $i < 60; $i++)
                                                    <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <input type="hidden" name="timer" id="timer" value="">
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

            <div id="question-details" style="display: none;" class="mb-5">
                <div id="questions-container"></div>
                <button type="submit" id="save-quiz-button" class="btn quiz-save-button">Save Quiz</button>
            </div>
        </form>
    </div>
@endsection

@push('custom-scripts')
    <script>
        const difficulties = @json($difficulties);
    </script>
    
    <script>
        // Handles when the "Next" button is clicked
        document.getElementById('next-button').addEventListener('click', function() {
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            const isPublic = document.getElementById('is_public').value;
            const startTime = document.getElementById('start_time').value;
            const endTime = document.getElementById('end_time').value;
            const timer = document.getElementById('timer').value;
            const totalQuestions = document.getElementById('total_question').value;
    
            // Check if all required fields are filled
            if (!title || !startTime || !endTime || !totalQuestions) {
                alert('Please fill out all fields before proceeding.');
                return;
            }
    
            // Show the question details section and hide quiz details
            document.getElementById('quiz-details').style.display = 'none';
            document.getElementById('question-details').style.display = 'block';
    
            const questionsContainer = document.getElementById('questions-container');
            questionsContainer.innerHTML = '';
    
            // Render the question form for each question
            for (let i = 0; i < totalQuestions; i++) {
                const questionHtml = `
                    <div class="card table-card mb-3">
                        <div class="card-header table-header">
                            <div class="table-title">Question ${i + 1}</div>
                        </div>
                        <div class="card-body custom-form">
                            <div class="row">
                                <div class="col-xxl-6 col-12">
                                    <label for="questions[${i}][question]" class="form-label custom-label">Question</label>
                                    <textarea name="questions[${i}][question]" class="form-control custom-input" style="resize: none;" placeholder="Enter question" required></textarea>
                                </div>
                                <div class="col-xxl-2 col-md-3 col-6">
                                    <label for="questions[${i}][question_difficulty]" class="form-label custom-label">Difficulty</label>
                                    <select name="questions[${i}][question_difficulty]" class="form-select custom-select" required>
                                        ${difficulties.map(difficulty => `<option value="${difficulty}">${difficulty}</option>`).join('')}
                                    </select>
                                </div>
                                <div class="col-xxl-2 col-md-3 col-6">
                                    <label for="questions[${i}][marks]" class="form-label custom-label">Marks</label>
                                    <input type="number" name="questions[${i}][marks]" class="form-control custom-input" required>
                                </div>
                                <div class="col-xxl-2 col-md-3 col-6">
                                    <label for="questions[${i}][type]" class="form-label custom-label">Type</label>
                                    <select name="questions[${i}][type]" class="form-select custom-select question-type-select" data-question-index="${i}" required>
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
    
        // Handles the change event for question type
        $(document).on('change', '.question-type-select', function(e) {
            const questionIndex = $(this).data('question-index');
            const optionsContainer = document.getElementById(`options-container-${questionIndex}`);
            optionsContainer.innerHTML = '';

            if ($(this).val() === 'radio' || $(this).val() === 'checkbox') {
                optionsContainer.innerHTML = `
                    <div class="col-xxl-2 col-md-3 col-6">
                        <label for="questions[${questionIndex}][total_options]" class="form-label custom-label">Total Options</label>
                        <input type="number" name="questions[${questionIndex}][total_options]" class="form-control custom-input total-options-input" data-question-index="${questionIndex}" required>
                    </div>
                `;
            }
        });

    
        // Handles the input event for total options
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('total-options-input')) {
                const questionIndex = e.target.getAttribute('data-question-index');
                const totalOptions = e.target.value;
                const optionsContainer = document.getElementById(`options-container-${questionIndex}`);
                const optionsHtml = [];
                for (let i = 0; i < totalOptions; i++) {
                    optionsHtml.push(`
                        <div class="col-xxl-2 col-md-3 col-6">
                            <label for="questions[${questionIndex}][options][${i}][option]" class="form-label custom-label">Option ${i + 1}</label>
                            <input type="text" name="questions[${questionIndex}][options][${i}][option]" class="form-control custom-input" required>
                        </div>
                    `);
                }

                optionsHtml.push(`
                    <div class="col-xxl-2 col-md-3 col-6">
                        <label for="questions[${questionIndex}][correct_option]" class="form-label custom-label">Correct Option(s)</label>
                        <select name="questions[${questionIndex}][correct_option][]" class="form-control custom-select multiple-select2" multiple required>
                            ${Array.from({ length: totalOptions }).map((_, i) => 
                                `<option value="${i}">Option ${i + 1}</option>`
                            ).join('')}
                        </select>
                    </div>
                `);
    
                const existingTotalOptionsField = `
                    <div class="col-xxl-2 col-md-3 col-6">
                        <label for="questions[${questionIndex}][total_options]" class="form-label custom-label">Total Options</label>
                        <input type="number" name="questions[${questionIndex}][total_options]" class="form-control custom-input total-options-input" data-question-index="${questionIndex}" value="${totalOptions}" required>
                    </div>
                `;
                optionsHtml.unshift(existingTotalOptionsField);
    
                optionsContainer.innerHTML = optionsHtml.join('');
                // Reinitialize Select2 for new elements
                $('.multiple-select2').select2({
                    placeholder: "Select Correct Answer",
                    allowClear: true
                });
            }
        });
    </script>
    
@endpush
