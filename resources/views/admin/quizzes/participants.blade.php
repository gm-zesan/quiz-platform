@extends('admin.app')

@section('title', 'Participants for Quiz: ' . $quiz->title)

@section('content')
<div class="container-fluid my-3">
    <div class="row">
        <div class="col-12">
            <div class="card table-card">
                <div class="card-header table-header">
                    <div class="title-with-breadcrumb">
                        <div class="table-title">Participants for Quiz: {{ $quiz->title }}</div>
                    </div>
                    
                    <a href="{{ route('admin.quizzes.index') }}" class="add-new">Back to Quizzes</a>
                </div>
                <div class="card-body" style="overflow-x: auto">
                    <table class="table dataTable w-100" id="data-table" style="min-width: 800px;">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 5%">SL NO</th>
                                <th scope="col" style="width: 20%">Name</th>
                                <th scope="col" style="width: 20%">Email</th>
                                <th scope="col" style="width: 20%">Submitted At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($participants as $index => $participant)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $participant->participant_name ?? 'N/A' }}</td>
                                    <td>{{ $participant->email ?? 'N/A' }}</td>
                                    <td>{{ $participant->submitted_at ? $participant->submitted_at->format('d M Y, h:i A') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="4">No participants have joined this quiz yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
