@extends('admin.app')
@section('title')
    Quizzes
@endsection



@push('custom-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.semanticui.min.css">
@endpush



@section('content')
    <div class="container-fluid my-3">
        <div class="row">
            <div class="col-12">
                <div class="card table-card">
                    <div class="card-header table-header">
                        <div class="title-with-breadcrumb">
                            <div class="table-title">Quizzes</div>
                            <nav aria-label="breadcrumb"> 
                                <ol class="breadcrumb mb-0"> 
                                    <li class="breadcrumb-item">
                                        <a href="{{route('admin.quizzes.index')}}">Dashboard</a>
                                    </li> 
                                    <li class="breadcrumb-item active" aria-current="page">Quizzes</li> 
                                </ol> 
                            </nav>
                        </div>
                        <a href="{{route('admin.quizzes.create')}}" class="add-new">Create Quizzes<i class="ms-1 ri-add-line"></i></a>
                    </div>
                    <div class="card-body" style="overflow-x: auto">
                        <table class="table dataTable w-100" id="data-table" style="min-width: 800px;">
                            <thead>
                                <tr>
                                    <th scope="col">SL NO</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Start Time</th>
                                    <th scope="col">End Time</th>
                                    <th scope="col">Created By</th>
                                    <th scope="col">Action</th>
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
@endsection


@push('custom-scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.js" defer></script>

    <script type="text/javascript">
        var listUrl = SITEURL + '/admin/quizzes';

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
                    { data: 'id', name: 'id', orderable: true },
                    { data: 'title', name: 'title', orderable: true },
                    { data: 'description', name: 'description', orderable: true },
                    { data: 'start_time', name: 'start_time', orderable: true },
                    { data: 'end_time', name: 'end_time', orderable: true },
                    { data: 'user_id', name: 'user_id', orderable: true },
                    {
                        data: 'action-btn',
                        orderable: false,
                        render: function(data) {
                            var btns = '<div class="action-btn">';
                            btns += '<form action="' + SITEURL + '/admin/quizzes/' + data +
                                '" method="POST" style="display: inline;">' +
                                '@csrf' +
                                '@method('PUT')' +
                                '<button type="submit" class="btn btn-edit" title="update status"><i class="ri-loop-right-line"></i></button>' +
                                '</form>';
                            btns += '<form action="' + SITEURL + '/admin/quizzes/' + data +
                                '" method="POST" style="display: inline;" onsubmit="return confirm(\'Are you sure to delete this review?\');">' +
                                '@csrf' +
                                '@method('DELETE')' +
                                '<button type="submit" class="btn btn-delete"><i class="ri-delete-bin-2-line"></i></button>' +
                                '</form>';
                            btns += '</div>';
                            return btns;
                        }
                    }
                ],
                order: [[0, 'asc']],
            });
        });
    </script>
@endpush
