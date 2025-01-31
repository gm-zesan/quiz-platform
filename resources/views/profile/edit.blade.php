@extends('admin.app')
@section('title')
    My Profile | Edit
@endsection

@push('custom-style')
    <style>
        .update_info_title{
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 0;
        }
        .update_info_subtitle{
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 0;
        }
        .content-body .table-card .custom-form .custom-label{
            font-size: 13px;
            margin-bottom: 2px;
        }
        .content-body .table-card .custom-form .custom-input{
            height: 36px;
            font-size: 13px;
        }
        .content-body .table-card .custom-form .custom-textarea{
            font-size: 13px;
            margin-bottom: 5px;
        }
        .error-messages{
            color: red;
            font-size: 14px;
            list-style: none;
            padding-left: 0;
        }
        .content-body .table-card .custom-form .image-select-file .custom-label .user-image .image-preview {
            width: auto;
            height: 95px;
            border-radius: 0;
            margin-bottom : 5px;
            border: none;
        }
        .content-body .table-card .custom-form .image-select-file .custom-label .user-image .no-image-preview {
            font-size: 64px;
            margin-bottom: 5px;
        }
        .image-select-file{
            border: 1px solid #e3e3e3;
        }
        .content-body .table-card .custom-form .submit-button{
            background-color: #000;
        }
        .content-body .table-card .custom-form .submit-button:hover{
            background-color: #6c757d;
        }
        
    </style>
@endpush

@section('content')
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-10 col-12 mx-auto">
                <div class="p-4 bg-white rounded-3">
                    <div class="row align-items-center">
                        <div class="col-md-12 col-12">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        $(document).ready(function(){
            $('#email').keyup(function(){
                var email = $(this).val();
                if(email == ''){
                    $('#setEmail').html('example@gmail.com');
                }else{
                    $('#setEmail').html(email);
                }
            });
        });
    </script>


    {{-- image upload and preview js --}}
    <script>
        function imageUpload( e ) {
            var imgPath = e.value;
            var ext = imgPath.substring( imgPath.lastIndexOf( '.' ) + 1 ).toLowerCase();
            if ( ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg") {
                readURL( e, e.id );
                $( '.' + e.id + 'error' ).hide()
                $( '#' + e.id + 'Delete' ).removeClass( 'd-none' );
            } else {
                $( '.' + e.id + 'error' ).html( 'Select a jpg, jpeg, png type image file.' ).show();
                $("#" + e.id + "_data").attr("value", "");
                $( '#' + e.id + 'Preview' ).attr( 'src', "" );
                $( '#' + e.id ).val( null );
                $( '#' + e.id + 'Delete' ).addClass( 'd-none' );
            }
        }

        var imageName;
        function readURL( input, id ) {
            if ( input.files && input.files[ 0 ] ) {
                imageName = input.files[0].name;
                var reader = new FileReader();
                reader.readAsDataURL( input.files[ 0 ] );
                reader.onload = function ( e ) {
                    console.log(id);
                    $( '#' + id + 'Preview' ).removeClass( 'd-none' );
                    $( '#' + id + 'PreviewNo' ).addClass( 'd-none' );
                    $( '#' + id + 'Preview' ).attr( 'src', e.target.result ).show();
                    $( '#' + id + 'Delete' ).css( 'display', 'flex' );
                    $( '#' + id + 'Delete' ).removeClass( 'd-none' );
                    $( '#' + id + 'Name' ).html( input.files[ 0 ].name );
                    $("#" + id + "_data").attr("value", imageName);
                    setProfileImage(e, imageName);
                };
            }
        }
        function removeImage(id) {
            $( "#" + id ).val( null );
            const imgValue = $( "#" + id + "Value" ).val();
            $('#' + id + 'Preview').addClass('d-none');
            $('#' + id + 'PreviewNo').removeClass('d-none');
            $( "#" + id + "_data").attr("value", "");
            $( '#' + id + 'Name' ).html( 'Not selected' );
            $( '#' + id + 'Delete' ).css( 'display', 'none' );
            $( '#' + id + 'Delete' ).addClass( 'd-none' );
            setProfileImage();
        }
    </script>
@endpush
