@extends('admin.app')
@section('title')
    Edit Quiz
@endsection

@push('custom-style')
    <style>
        .select2-container.select2-container--default {
            max-width: 694.656px;
            width: 100% !important;
        }
        .form-label {
            font-weight: bold;
            color: #555;
        }
        textarea.form-control {
            resize: none;
        }

        .question-container {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
        }
        .form-check-label {
            font-size: 0.9rem;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
        }
        .question-container:hover {
            background-color: #f1f1f1;
        }
        #questions-container {
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .error_msg {
            color: red;
            font-size: 0.9rem;
        }

    </style>
@endpush


@section('content')


<div class="container-fluid my-3">
    <form action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <div class="col-md-8 col-12">
                <div class="card table-card">
                    <div class="card-header table-header">
                        <div class="title-with-breadcrumb">
                            <div class="table-title">Edit Quiz</div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quizzes</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit Quiz</li>
                                </ol>
                            </nav>
                        </div>
                        <a href="{{ route('admin.quizzes.index') }}" class="add-new">Quizzes<i class="ms-1 ri-list-ordered-2"></i></a>
                    </div>
                    <div class="card-body custom-form">
                        <div class="row">
                            <!-- Quiz Title -->
                            <div class="col-12">
                                <label for="title" class="form-label custom-label">Title</label>
                                <input type="text" class="form-control custom-input" name="title" value="{{ old('title', $quiz->title) }}" required>
                                @if($errors->has('title'))
                                    <div class="error_msg">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
    
                            <!-- Quiz Description -->
                            <div class="col-md-12">
                                <label for="description" class="form-label custom-label">Description</label>
                                <textarea class="form-control custom-input" name="description" id="description" rows="5" placeholder="Description" style="resize: none; height: auto">{{ old('description', $quiz->description) }}</textarea>
                                @if($errors->has('description'))
                                    <div class="error_msg">{{ $errors->first('description') }}</div>
                                @endif
                            </div>
    
                            <!-- Quiz Start Time -->
                            <div class="col-md-6 col-12">
                                <label for="start_time" class="form-label custom-label">Start Time</label>
                                <input type="datetime-local" name="start_time" id="start_time" class="form-control custom-input" value="{{ old('start_time', $quiz->start_time->format('Y-m-d\TH:i')) }}" required>
                                @if($errors->has('start_time'))
                                    <div class="error_msg">{{ $errors->first('start_time') }}</div>
                                @endif
                            </div>
    
                            <!-- Quiz End Time -->
                            <div class="col-md-6 col-12">
                                <label for="end_time" class="form-label custom-label">End Time</label>
                                <input type="datetime-local" name="end_time" id="end_time" class="form-control custom-input" value="{{ old('end_time', $quiz->end_time->format('Y-m-d\TH:i')) }}" required>
                                @if($errors->has('end_time'))
                                    <div class="error_msg">{{ $errors->first('end_time') }}</div>
                                @endif
                            </div>
    
                            <!-- Quiz Timer -->
                            <div class="col-md-6 col-12">
                                <label for="timer" class="form-label custom-label">Timer (Minutes)</label>
                                <div class="d-flex gap-2 align-items-center">
                                    <select name="hours" id="hours" class="form-select single-select2">
                                        <option value="" disabled>Hours</option>
                                        @for ($i = 0; $i < 24; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}" {{ isset($hours) && $hours == $i ? 'selected' : '' }}>
                                                {{ sprintf('%02d', $i) }}
                                            </option>
                                        @endfor
                                    </select>
                            
                                    <select name="minutes" id="minutes" class="form-select single-select2">
                                        <option value="" disabled>Minutes</option>
                                        @for ($i = 0; $i < 60; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}" {{ isset($minutes) && $minutes == $i ? 'selected' : '' }}>
                                                {{ sprintf('%02d', $i) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <input type="hidden" name="timer" id="timer" value="{{ old('timer', $quiz->timer) }}">
                                @if($errors->has('timer'))
                                    <div class="error_msg">{{ $errors->first('timer') }}</div>
                                @endif
                            </div>
    
                            <!-- Quiz Questions -->
                            <div class="col-12">
                                <div id="questions-container">
                                    @foreach ($quiz->questions as $index => $question)
                                    <div class="question-container card mb-4 p-4 shadow-sm rounded border border-primary border-2">
                                        <div class="row">
                                            <div class="col-md-8 col-6">
                                                <div class="form-group mb-3">
                                                    <label for="questions[{{ $index }}][question]" class="form-label custom-label text-muted">Question</label>
                                                    <textarea name="questions[{{ $index }}][question]" class="form-control custom-input" rows="3" required>{{ old('questions.' . $index . '.question', $question->question) }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-2 col-3">
                                                <div class="form-group mb-3">
                                                    <label for="questions[{{ $index }}][question_difficulty]" class="form-label custom-label text-muted">Difficulty</label>
                                                    <select name="questions[{{ $index }}][question_difficulty]" class="form-control custom-input" required>
                                                        <option value="Easy" {{ old('questions.' . $index . '.question_difficulty', $question->question_difficulty) == 'Easy' ? 'selected' : '' }}>Easy</option>
                                                        <option value="Medium" {{ old('questions.' . $index . '.question_difficulty', $question->question_difficulty) == 'Medium' ? 'selected' : '' }}>Medium</option>
                                                        <option value="Hard" {{ old('questions.' . $index . '.question_difficulty', $question->question_difficulty) == 'Hard' ? 'selected' : '' }}>Hard</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2 col-3">
                                                <div class="form-group mb-3">
                                                    <label for="questions[{{ $index }}][marks]" class="form-label custom-label text-muted">Marks</label>
                                                    <input type="number" name="questions[{{ $index }}][marks]" class="form-control custom-input" value="{{ old('questions.' . $index . '.marks', $question->marks) }}" required>
                                                </div>
                                            </div>

                                        </div>

                                        
                                        @if (in_array($question->type->value, ['radio', 'checkbox']))
                                        <div id="options-container-{{ $index }}" class="px-4">
                                            <div class="row">
                                                @foreach ($question->options as $option)
                                                <div class="col-md-6 col-12">
                                                    <input type="hidden" name="questions[{{ $index }}][options][{{ $loop->index }}][id]" value="{{ $option->id }}">
                                                    <label for="questions[{{ $index }}][options][{{ $loop->index }}]" class="form-label custom-label">Option {{ $loop->index + 1 }}</label>
                                                    @if($question->type->value == 'radio')
                                                        <input type="radio" 
                                                            name="questions[{{ $index }}][is_correct]"
                                                            value="{{ $option->id }}"
                                                            class="form-check-input custom-checkbox ms-2" 
                                                            {{ $option->is_correct ? 'checked' : '' }}>
                                                    @elseif($question->type->value == 'checkbox')
                                                        <input type="checkbox" 
                                                            name="questions[{{ $index }}][options][{{ $loop->index }}][is_correct]"
                                                            class="form-check-input custom-checkbox ms-2" 
                                                            {{ $option->is_correct ? 'checked' : '' }}>
                                                    @endif


                                                    <input type="text" name="questions[{{ $index }}][options][{{ $loop->index }}][option]" class="form-control custom-input" value="{{ old('questions.' . $index . '.options.' . $loop->index . '.option', $option->option) }}" required>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                        
                                        
                            
                                        
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="col-md-4 col-12">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card table-card">
                            <div class="table-header">
                                <div class="table-title">Actions</div>
                            </div>
                            <div class="custom-form card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn submit-button">Update
                                            <span class="ms-1 spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('admin.quizzes.index') }}" class="btn leave-button">Leave</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
</div>

@endsection

@push('custom-scripts')
    <script>
        $('.submit-button').click(function(){
            $(this).css('opacity', '1');
            $(this).find('.spinner-border').removeClass('d-none');
            $(this).attr('disabled', true);
            $(this).closest('form').submit();
        });
    </script>


@endpush
