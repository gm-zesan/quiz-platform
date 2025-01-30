@extends('admin.app')

@section('title')
    Dashboard
@endsection


@push('custom-style')
   {{-- Datatable css  --}}
   <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.semanticui.min.css">
   <style>
    .card{
        border-radius: 10px;
    }
   </style>
@endpush

@section('content')


    <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h6 class="p-0 m-0">Welcome {{ auth()->user()->name }}!</h6>
                        <p class="p-0 m-0">
                            Remaining quiz: <span class="fw-bold">{{ config('quiz.quiz_count') - auth()->user()->created_quiz_count }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="p-0 m-0">Quiz Created: <span class="fw-bold">{{ auth()->user()->created_quiz_count }}</span></h6>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <p class="p-0 m-0">
                                <a href="{{ route('admin.quizzes.index') }}" class="btn custom-button-primary mb-0">View all</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="p-0 m-0">Total participants: <span class="fw-bold">{{ $total_participants }}</span></h6>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="p-0 m-0">Average score: <span class="fw-bold">{{ round($average_score, 2) }}%</span></h6>
                        
                    </div>
                </div>
            </div>


        </div>
    </div>



    {{-- <div class="container-fluid my-3">
        
        <div class="row mb-5">
            <div class="col-md-4">
                @canany(['student-list', 'student-create', 'student-edit', 'student-delete'])
                    <div class="card dashboard-card">
                        <div class="card-body target-bg">
                            <div class="dashboard-icon">
                                <a href="{{ route('students.index') }}"><i class="ri-user-3-line"></i></a>
                            </div>
                            <div class="dashboard-info">
                                <h4 class="target-title">Students</h4>
                                <h3 class="numbers"> {{ $student_count }} + </h3>
                                <a href="{{ route('students.index') }}">View all<i class="ms-2 ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>

            <div class="col-md-4">
                @canany(['course-list', 'course-create', 'course-edit', 'course-delete'])
                    <div class="card dashboard-card">
                        <div class="card-body target-bg non">
                            <div class="dashboard-icon">
                                <a href="{{ route('courses.index') }}"><i class="ri-file-list-line"></i></a>
                            </div>
                            <div class="dashboard-info">
                                <h4 class="target-title">Courses</h4>
                                <h3 class="numbers"> {{ $course_count }} + </h3>
                                <a href="{{ route('courses.index') }}">View all<i class="ms-2 ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
            
            <div class="col-md-4">
                @canany(['teacher-list', 'teacher-create', 'teacher-edit', 'teacher-delete'])
                    <div class="card dashboard-card">
                        <div class="card-body target-bg">
                            <div class="dashboard-icon">
                                <a href="{{ route('teachers.index') }}"><i class="ri-user-3-line"></i></a>
                            </div>
                            <div class="dashboard-info">
                                <h4 class="target-title">Teacher</h4>
                                <h3 class="numbers"> {{ $teacher_count }} + </h3>
                                <a href="{{ route('teachers.index') }}">View all<i class="ms-2 ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>

        <div class="row">
            @canany(['contact-list', 'contact-delete'])
                <div class="col-12">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card table-card dashboard-table">
                                <div class="card-header table-header">
                                    <div class="title-with-breadcrumb">
                                        <div class="table-title">Contact Messages</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table w-100" id="data-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Subject</th>
                                                <th scope="col">Messages</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div> --}}
@endsection

@push('custom-scripts')
    {{-- Data table --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.js" defer></script>



    {{-- Datatable Ajax Call --}}
    <script type="text/javascript">
        var listUrl = SITEURL + '/dashboard/message';

        $(document).ready( function () {
            var table = $('#data-table').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                fixedHeader: true,
                "pageLength": 20,
                "lengthMenu": [ 20, 50, 100, 500 ],
                ajax: {
                    url: listUrl,
                    type: 'GET'
                },
                columns: [
                    { data: 'name', name: 'name', orderable: true },
                    { data: 'email', name: 'email', orderable: true },
                    { data: 'subject', name: 'subject', orderable: true },
                    { data: 'message', name: 'message', orderable: true },
                ],
                order: [[0, 'asc']]
            });
        });
    </script>


@endpush
