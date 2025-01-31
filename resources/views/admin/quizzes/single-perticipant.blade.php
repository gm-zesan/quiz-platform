@extends('admin.app')

@section('title', 'Single Participant')

@section('content')


<div class="container-fluid my-3">
  <div class="row">
      <div class="col-12">
          <div class="card table-card">
              <div class="card-header table-header">
                <div class="title-with-breadcrumb">
                    <div class="table-title">Quiz Review</div>
                </div>
                
                <a href="{{ route('admin.quizzes.index') }}" class="add-new">Back to Quizzes</a>
              </div>
              <div class="card-body" style="overflow-x: auto">
                <div class="row">
                  <div class="col-12">
                    <h4>Quiz Result for Participant: {{ $participant->email }}({{ $participant->name ?? 'N/A' }})</h4>
                  </div>
                  <div class="col-12">
                    <div class="flex justify-between row">
                      <div class="col-6">
                        <h6 class="mb-0">Quiz: {{ $quiz->title }}</h6>
                        <h6 class="mb-0 mt-1">Score: {{ $participant->score }}
                          <span class="m-0 ms-2 custom-button-primary-sm" onclick="showUpdateForm(this)">Update</span>  
                        </h6>
                        <div id="update-form" class="mt-2" style="display: none;">
                          <form action="{{ route('admin.quizzes.participant.update', [$quiz->id, $participant->id]) }}" method="POST" >
                            @csrf
                            @method('PUT')
                            <div class="d-flex justify-content-between align-items-center">
                              <input type="number" name="score" class="form-control me-2" value="{{ $participant->score }}">
                              <button type="submit" class="btn custom-button-primary m-0 ">Update Mark</button>
                            </div>
                          </form>
                        </div>
                      </div>
                      <div class="col-6 text-end">
                        <h6 class="mb-0">Submitted: {{ $participant->submitted_at->format('d M Y h:i A') }}</h6>
                        <h6 class="mb-0">Time Taken: {{ $participant->submitted_at->diffForHumans($participant->created_at) }}</h6>
                      </div>
                    </div>

                    <div class="mt-3">
                      @foreach ($quiz->questions as $question)
                          <div class="card mb-3">
                            <div class="card-body">
                              <p class="card-title mb-0">Question: <strong>{{ $question->question }}</strong></p>
                              <p class="card-text m-0">Mark: <strong>{{ $question->marks }}</strong></p>
                              <p class="card-text m-0 mb-2">Type:
                                @if ($question->type->value === 'radio')
                                  <span class=" text-muted font-sm">Single Choice</span>
                                @elseif ($question->type->value === 'checkbox')
                                  <span class=" text-muted font-sm">Multiple Choice</span>
                                @else
                                  <span class=" text-muted font-sm">Text</span>
                                @endif
                              </p>
                                @if ($question->type->value === 'radio' || $question->type->value === 'checkbox')
                                  <div>
                                    @foreach ($question->options as $option)
                                      <div class="mb-0">
                                        @if (isset($responses[$question->id]) && in_array($option->id, (array) $responses[$question->id]->option_id))
                                            <div style="width: 10px; height: 10px; border-radius: 50%; display: inline-block; background-color: #008064;"></div>
                                            <span class=" text-xs text-success">{{ $option->option }}</span>
                                        @elseif ($option->is_correct)
                                            <div style="width: 10px; height: 10px; border-radius: 50%; display: inline-block; background-color: #fd3d3d;"></div>
                                            <span class=" text-xs text-danger">{{ $option->option }}</span>
                                        @else
                                            <div style="width: 10px; height: 10px; border-radius: 50%; display: inline-block; background-color: #4e4e4e;"></div>
                                            <span class=" text-xs text-muted">{{ $option->option }}</span>
                                        @endif
                                      </div>
                                    @endforeach
                                  </div>
                                @else
                                    <p name="responses[{{ $question->id }}]">{{ $responses[$question->id]->answer ?? '' }}</p>
                                @endif
                            </div>
                          </div>
                      @endforeach
                    </div>

                  </div>
                </div>
              </div>
          </div>
      </div>
  </div>
</div>

<script>
  function showUpdateForm(element) {
    const updateForm = document.getElementById('update-form');
    if (updateForm.style.display === 'none') {
      updateForm.style.display = 'block';
    } else {
      updateForm.style.display = 'none';
    }
  }
</script>

@endsection