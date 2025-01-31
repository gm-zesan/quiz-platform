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
                                        <textarea class="form-control custom-textarea" name="description" id="description" rows="5"></textarea>
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
                                            id="start_time" required value="{{ now()->format('Y-m-d\TH:i') }}">
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="end_time" class="form-label custom-label">End Time</label>
                                        <input type="datetime-local" class="form-control custom-input" name="end_time"
                                            id="end_time" required value="{{ now()->addDays(1)->format('Y-m-d\TH:i') }}">
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
                                        <label for="total_question" class="form-label custom-label">Number of Questions</label>
                                        <input type="number" class="form-control custom-input" name="total_question"
                                            id="total_question" required value="1">
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
                <div class="row">
                    <div class="col-md-3 col-12">
                        <button type="button" id="add-question-button" class="btn custom-button-secondary" onclick="addQuestion()">Add Question</button>
                    </div>
                    <div class="col-md-3 col-12">
                        <button type="submit" id="save-quiz-button" class="btn custom-button-primary">Save Quiz</button>
                    </div>
                </div>
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
                                <div class="col-xxl-2 col-md-3 col-6" id="addOptions-${i}" style="display: none;">
                                    <label class="form-label custom-label">Add Options</label>
                                    <button type="button" class="btn custom-button-primary add-more-options" data-question-index="${i}">Add Options</button>
                                    <input type="hidden" name="questions[${i}][total_options]" value="1">
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
    
        // Handles the input event for total options
        $(document).on('input', 'input[name*="[options]"]', function(e) {
            const input = $(this);
            const questionIndex = input.attr('name').match(/\[(\d+)\]\[options\]/)[1];
            const optionIndex = parseInt(input.attr('name').match(/\[(\d+)\]\[option\]/)[1]);
            const optionsContainer = $(`#options-container-${questionIndex}`);
            
            // Check if this is the last option and it has value
            if (input.val().trim() !== '' && optionIndex === optionsContainer.find('input[name*="[options]"]').length - 1) {
                // Add new empty option
                const newOptionIndex = optionIndex + 1;
                optionsContainer.append(`
                    <div class="col-xxl-2 col-md-3 col-6">
                        <label for="option-${questionIndex}-${newOptionIndex}" class="form-label custom-label">Option ${newOptionIndex + 1}</label>
                        <input type="text" id="option-${questionIndex}-${newOptionIndex}" 
                               name="questions[${questionIndex}][options][${newOptionIndex}][option]" 
                               class="form-control custom-input">
                    </div>
                `);
                
                // Update total options input
                $(`input[name="questions[${questionIndex}][total_options]"]`).val(newOptionIndex + 1);

                // Update correct options select
                const correctOptionsSelect = $(`#correct-options-${questionIndex}`);
                correctOptionsSelect.append(`<option value="${newOptionIndex}">Option ${newOptionIndex + 1}</option>`);
            }
        });

        // Handles the change event for question type
        $(document).on('change', '.question-type-select', function(e) {
            const questionIndex = $(this).data('question-index');
            const optionsContainer = $(`#options-container-${questionIndex}`);
            optionsContainer.html('');

            if ($(this).val() === 'radio' || $(this).val() === 'checkbox') {
                // Show first option and correct options select
                optionsContainer.html(`
                    <div class="col-xxl-2 col-md-3 col-6">
                        <label for="correct-options-${questionIndex}" class="form-label custom-label">Correct Option(s)</label>
                        <select id="correct-options-${questionIndex}" 
                                name="questions[${questionIndex}][correct_option][]" 
                                class="form-control custom-select multiple-select2" 
                                multiple required>
                            <option value="0">Option 1</option>
                        </select>
                    </div>
                    <div class="col-xxl-2 col-md-3 col-6">
                        <label for="option-${questionIndex}-0" class="form-label custom-label">Option 1</label>
                        <input type="text" id="option-${questionIndex}-0" 
                               name="questions[${questionIndex}][options][0][option]" 
                               class="form-control custom-input" required>
                    </div>
                `);
                
                // Initialize Select2
                $(`#correct-options-${questionIndex}`).select2({
                    placeholder: "Select Correct Answer",
                    allowClear: true
                });

                // Show the add more options button
                $(`#addOptions-${questionIndex}`).show();
            }else{
                // Hide the add more options button
                $(`#addOptions-${questionIndex}`).hide();
            }
        });

        // Handles adding more options manually
        $(document).on('click', '.add-more-options', function() {
            const questionIndex = $(this).data('question-index');
            const optionsContainer = $(`#options-container-${questionIndex}`);
            const currentOptionsCount = optionsContainer.find('input[name*="[options]"]').length;
            
            optionsContainer.append(`
                <div class="col-xxl-2 col-md-3 col-6">
                    <label for="option-${questionIndex}-${currentOptionsCount}" class="form-label custom-label">Option ${currentOptionsCount + 1}</label>
                    <input type="text" id="option-${questionIndex}-${currentOptionsCount}" 
                           name="questions[${questionIndex}][options][${currentOptionsCount}][option]" 
                           class="form-control custom-input">
                </div>
            `);

            // Update total options input
            $(`input[name="questions[${questionIndex}][total_options]"]`).val(currentOptionsCount + 1);
            
            // Update correct options select
            const correctOptionsSelect = $(`#correct-options-${questionIndex}`);
            correctOptionsSelect.append(`<option value="${currentOptionsCount}">Option ${currentOptionsCount + 1}</option>`);
        });


        function addQuestion() {
            var questionsContainer = document.getElementById('questions-container');
            var totalQuestions = Number(document.getElementById('total_question').value);

            const newQuestion = document.createElement('div');
            newQuestion.classList.add('card', 'table-card', 'mb-3');
            newQuestion.innerHTML = `
                <div class="card-header table-header">
                    <div class="table-title">Question ${totalQuestions + 1}</div>
                </div>
                <div class="card-body custom-form">
                    <div class="row">
                        <div class="col-xxl-6 col-12">
                            <label for="questions[${totalQuestions}][question]" class="form-label custom-label">Question</label>
                            <textarea name="questions[${totalQuestions}][question]" class="form-control custom-input" style="resize: none;" placeholder="Enter question" required></textarea>
                        </div>
                        <div class="col-xxl-2 col-md-3 col-6">
                            <label for="questions[${totalQuestions}][question_difficulty]" class="form-label custom-label">Difficulty</label>
                            <select name="questions[${totalQuestions}][question_difficulty]" class="form-select custom-select" required>
                                ${difficulties.map(difficulty => `<option value="${difficulty}">${difficulty}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-xxl-2 col-md-3 col-6">
                            <label for="questions[${totalQuestions}][marks]" class="form-label custom-label">Marks</label>
                            <input type="number" name="questions[${totalQuestions}][marks]" class="form-control custom-input" required>
                        </div>
                        <div class="col-xxl-2 col-md-3 col-6">
                            <label for="questions[${totalQuestions}][type]" class="form-label custom-label">Type</label>
                            <select name="questions[${totalQuestions}][type]" class="form-select custom-select question-type-select" data-question-index="${totalQuestions}" required>
                                <option value="short_text">Short Text</option>
                                <option value="long_text">Long Text</option>
                                <option value="radio">Radio</option>
                                <option value="checkbox">Checkbox</option>
                            </select>
                        </div>
                        <div class="col-xxl-2 col-md-3 col-6" id="addOptions-${totalQuestions}" style="display: none;">
                            <label class="form-label custom-label">Add Options</label>
                            <button type="button" class="btn custom-button-primary add-more-options" data-question-index="${totalQuestions}">Add Options</button>
                            <input type="hidden" name="questions[${totalQuestions}][total_options]" value="1">
                        </div>
                        <div class="col-12">
                            <div class="row" id="options-container-${totalQuestions}"></div>
                        </div>
                    </div>
                </div>
            `;
            questionsContainer.appendChild(newQuestion);
            totalQuestions++;
            document.getElementById('total_question').value = totalQuestions;
        }


    </script>
    
@endpush
