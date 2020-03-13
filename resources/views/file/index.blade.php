@extends('layouts.app')

@section('content')

    <div style="height: 20px;"></div>

    <div class="row">
        <div class="col-12 col-sm-12 col-md-11 col-lg-10 col-xl-9 mx-auto">
            <div class="row mt-3">
                <div class="col">
                    <div class="">
                        <strong style="font-size: 20px;">Your Images</strong>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-3">
                    <button class="btn btn-primary" id="image_modal"> Upload Image</button>
                </div>
                <div class="col-9">
                    <form id="search_form">
                        <input type="text" class="form-control" name="search" id="search" placeholder="Search....">
                    </form>
                </div>
            </div>
            <hr>
            <div class="row mt-3" id="image_show">
{{--                <div class="col-2">--}}
{{--                    <img src="storage/image/file/1584095335.jpg" width="100" height="100">--}}
{{--                    <h3>Title</h3>--}}
{{--                    <button type="button" class="btn btn-primary">Remove</button>--}}
{{--                </div>--}}
{{--                <div class="col-2" id="image_show">--}}
{{--                    <img src="storage/image/file/1584095335.jpg" width="100" height="100">--}}
{{--                    <h3>Title</h3>--}}
{{--                    <button type="button" class="btn btn-primary">Remove</button>--}}
{{--                </div>--}}
            </div>


            <div class="modal fade" id="image_upload_modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload Image</h5>
                            <button type="button" class="close image_upload_modal_close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body" style="padding-left: 80px; padding-right: 80px; padding-bottom: 50px;">
                            <div id="religion_form_message" class="text-center text-danger">

                            </div>
                            <form id="image_upload_form" enctype="multipart/form-data">
                                <input name="id" type="hidden" id="image_id">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label >
                                                <input type="file" name="image"  onchange="readPhotoURL(this);" style="opacity:0;">
                                                <img  style="width:80px; height:80px"; id="image" src="{{asset('storage/icon/upload.png')}}" alt="your image" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Drag & Drop or Select File
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="religion">Image Title</label>
                                            <input name="title" type="text" class="form-control" id="title" placeholder="Image Title">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col text-right">
                                        <button type="submit" class="btn btn-primary btn-sm text-center margin_left_fifteen_px" id="image_upload_form_submit"></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div style="height: 100px;"></div>

    <script language="JavaScript">

        let currentPageUrl = '';

        function getImages(url)
        {
            // $('#image_show').empty();
            $.ajax({
                method: 'get',
                url: url,
                success: function (result) {
                    console.log(result);
                    $.each(result, function (key, data) {
                        console.log(data.image_path);


                        $('#image_show').append($('<div class="col-2">')
                            .append("<img class='p-3' src='storage/"+ data.image_path +"' width='130' height='130' >")
                            .append("<h3> " + data.title + "</h3>")
                            .append('<button type="button" class="btn btn-primary remove_image" data-id="' + data.id + '">Remove</button>')
                            .append('</div>')
                        );

                        // $('#image_show').append('<div class="col">')
                        //     .append("<img class='p-3' src='storage/"+ data.image_path +"' width='130' height='130' >")
                        //     .append("<h3> " + data.title + "</h3>")
                        //     .append('<button type="button" class="btn btn-primary remove_image" data-id="' + data.id + '">Remove</button>')
                        //     .append('</div>');


                        //$('#image_show').append("<img class='p-3' src='storage/"+ data.image_path +"' width='130' height='130' >");
                        //$('#image_show').append("<h3> " + data.title + "</h3>");
                        // $('#image_show').append("<button type='button' class='btn btn-primary image_remove'" data-id="' + data.id +'" >Remove</button>");
                        //$('#image_show').append('<button type="button" class="btn btn-primary remove_image" data-id="' + data.id + '">Remove</button>')
                    })
                },
                error: function (xhr) {
                    console.log(xhr);
                }
            });
            return true;
        }

        $(document).ready(function () {
           console.log('hello');
           currentPageUrl = '{{ url('get/image') }}/null';
           getImages(currentPageUrl);

        });

        $(document).on('keyup', '#search', function () {
            let searchKey = $('#search').val() === '' ? 'null' : $('#search').val();
            currentPageUrl = '{{ url('get/image') }}/' + searchKey;
            getImages(currentPageUrl);
            return false;
        });

        $('#image_modal').on('click', function () {
            $('#image_upload_form_submit').text('SAVE');
            $('#image_upload_modal').modal('show').on('shown.bs.modal', function () {
                $('#religion').focus();
            });
            return false;
        });

        function readPhotoURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#image')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).on('submit', '#image_upload_form' ,function () {
           let fileData = new FormData(this);
            fileData.append('_token', '{{ csrf_token() }}');
           $.ajax({
               method: 'post',
              url: '{{ url('image/upload') }}',
               data: fileData,
               processData: false,
               contentType: false,
               cache: false,
               success: function (result) {
                   console.log(result);
               },
               error: function (xhr) {
                   console.log(xhr);
               }
               
           });
           return false;
        });

        $(document).on('click', '.remove_image', function () {
           let id = $(this).data('id');
           console.log(id);
           $.ajax({
               method: 'get',
               url: '{{ url('image/file/remove') }}/' + id,
               cache: false,
               success: function (result) {
                   console.log(result);
               },
               error: function (xhr) {
                   console.log(xhr);
               }
           });
           return false;
        });

    </script>

@endsection