@extends('layouts.app')

@section('content')
    <style>
        .image_progress { position:relative; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
        .image_bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
        .image_percent { position:absolute; display:inline-block; top:3px; left:48%; }
    </style>

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
                    <input type="text" class="form-control" name="search" id="search" placeholder="Search....(Case Sensiitve)">
                </div>
            </div>
            <hr>
            <div class="row mt-3" id="image_show">

            </div>

            <div class="row sr-only" id="image_no_record_section">
                <div class="col text-center">
                    No Record Found
                </div>
            </div>


            <div class="modal fade" id="image_upload_modal">
                <div class="modal-dialog modal-dialog-centered modal-fifty">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload Image</h5>
                            <button type="button" class="close image_upload_modal_close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body" style="padding-left: 80px; padding-right: 80px; padding-bottom: 50px;">
                            <div id="image_form_message" class="text-center text-danger">

                            </div>
                            <form id="image_upload_form" enctype="multipart/form-data">
                                <input name="id" type="hidden" id="image_id">
                                <div class="row" id="drop-container">
                                    <div class="col text-center">
                                        <div class="form-group">
                                            <input type="file" name="image" onchange="readPhotoURL(this)" style="opacity: 0; width: 200px; height: 150px; border: 1px solid grey; cursor: pointer;">
                                            <img  style="width:200px; height:150px; margin-top: 117px; margin-left: -204px; border: 1px solid grey;" id="image" src="{{url('image/icon/upload.png')}}" alt="your photo" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col text-center">
                                        Drag & Drop or Select File
                                    </div>
                                </div>
                                <hr style="border-top: 1px dashed;">
                                <div class="row mt-3">
                                    <div class="col-4 text-right mt-2">
                                        <div class="form-group">
                                            <label for="title">Image Title</label>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="form-group">
                                            <input name="title" type="text" class="form-control" id="title" placeholder="Image Title">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col text-center">
                                        <button type="submit" class="btn btn-primary btn-sm text-center margin_left_fifteen_px" id="image_upload_form_submit"></button>
                                    </div>
                                </div>
                            </form>

                            <div class="row mt-3">
                                <div class="col">
                                    <div class="image_progress">
                                        <div class="image_bar"></div >
                                        <div class="image_percent">0%</div >
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div style="height: 100px;"></div>

    <script language="JavaScript">

        let currentPageUrl = '';
        let allData;

        function getImages(url)
        {
            $('#image_no_record_section').addClass('sr-only');
            $('#image_show').empty();
            $.ajax({
                method: 'get',
                url: url,
                success: function (result) {
                    console.log(result);
                    allData = result;
                    if(result && result.length) {
                        console.log('record');
                        $.each(result, function (key, data) {
                            console.log(data.image_path);
                            $('#image_show').prepend(
                                '<div class="col-2 mt-4">' +
                                '<div class="row">' +
                                '<div class="col">' +
                                '<a href="storage/'+ data.image_path +'"><img class="border" src="storage/'+ data.image_path +'" width="130" height="130" alt=""></a>' +
                                '</div>' +
                                '</div>' +
                                '<div class="row mt-2">' +
                                '<div class="col text-center">' +
                                '<span>' + data.title + '</span>' +
                                '</div>' +
                                '</div>' +
                                '<div class="row mt-2">' +
                                '<div class="col text-center">' +
                                '<button type="button" class="btn btn-primary btn-sm remove_image" data-id="'+ data.id +'">Remove</button>' +
                                '</div>' +
                                '</div>' +
                                '</div>'
                            );
                        });
                    } else {
                        console.log(' no record');
                        $('#image_no_record_section').removeClass('sr-only');
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                }
            });
            return true;
        }

        $(document).ready(function () {
           currentPageUrl = '{{ url('get/image') }}';
           getImages(currentPageUrl);

        });

        function clearFileForm() {
            $('#image_form_message').empty();
            $('#image_upload_form').find('.text-danger').removeClass('text-danger');
            $('#image_upload_form').find('.is-invalid').removeClass('is-invalid');
            $('#image_upload_form').find('span').remove();
            return true;
        }

        $(document).on('keyup', '#search', function () {
            $('#image_no_record_section').addClass('sr-only');
            $('#image_show').empty();
            if($('#search').val() === '') {
                $.each(allData, function (key, data) {
                    $('#image_show').prepend(
                        '<div class="col-2 mt-4">' +
                        '<div class="row">' +
                        '<div class="col">' +
                        '<a href="storage/'+ data.image_path +'"><img class="border" src="storage/'+ data.image_path +'" width="130" height="130" alt=""></a>' +
                        '</div>' +
                        '</div>' +
                        '<div class="row mt-2">' +
                        '<div class="col text-center">' +
                        '<span>' + data.title + '</span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="row mt-2">' +
                        '<div class="col text-center">' +
                        '<button type="button" class="btn btn-primary btn-sm remove_image" data-id="'+ data.id +'">Remove</button>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );
                });
            } else {
                let found = false;
                $.each(allData, function (key, data) {
                    if (data.title === $('#search').val()) {
                        found = true;
                        $('#image_no_record_section').addClass('sr-only');
                        $('#image_show').prepend(
                            '<div class="col-2 mt-4">' +
                            '<div class="row">' +
                            '<div class="col">' +
                            '<a href="storage/'+ data.image_path +'"><img class="border" src="storage/'+ data.image_path +'" width="130" height="130" alt=""></a>' +
                            '</div>' +
                            '</div>' +
                            '<div class="row mt-2">' +
                            '<div class="col text-center">' +
                            '<span>' + data.title + '</span>' +
                            '</div>' +
                            '</div>' +
                            '<div class="row mt-2">' +
                            '<div class="col text-center">' +
                            '<button type="button" class="btn btn-primary btn-sm remove_image" data-id="'+ data.id +'">Remove</button>' +
                            '</div>' +
                            '</div>' +
                            '</div>'
                        );
                    } else if (found === false){
                        $('#image_no_record_section').removeClass('sr-only');
                    }
                });
            }
            return false;
        });

        $('#image_modal').on('click', function () {
            $('#image_upload_form').trigger('reset');
            $('#image').attr('src', '{{ url('image/icon/upload.png') }}');
            clearFileForm();
            $('#image_upload_form_submit').text('Upload');
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
            clearFileForm();
            var bar = $('.image_bar');
            var percent = $('.image_percent');
            let fileData = new FormData(this);
            fileData.append('_token', '{{ csrf_token() }}');
           $.ajax({
               method: 'post',
               url: '{{ url('image/upload') }}',
               data: fileData,
               processData: false,
               contentType: false,
               cache: false,
               xhr: function() {
                   var xhr = new window.XMLHttpRequest();
                   xhr.upload.addEventListener("progress", function(evt) {
                       //console.log(percentComplete);
                       if (evt.lengthComputable) {
                           var percentComplete = Math.round( (evt.loaded * 100) / evt.total ) + '%';
                           //var barVal = Math.ceil(percentComplete);
                           bar.width(percentComplete);
                           percent.html(percentComplete);
                       }
                   }, false);
                   return xhr;
               },
               success: function (result) {
                   $.toaster({ title: 'Success', priority : 'success', message : 'Image Updated Successfully' });
                   console.log(result);
                   var percentVal = '100%';
                   bar.width(percentVal);
                   percent.html(percentVal);
                   currentPageUrl = '{{ url('get/image') }}';
                   getImages(currentPageUrl);
                   $('#image_upload_form').trigger('reset');
                   $('#image').attr('src', '{{ url('image/icon/upload.png') }}');
                   //$('.image_upload_modal_close').trigger('click');
               },
               error: function (xhr) {
                   console.log(xhr);
                   if (xhr.hasOwnProperty('responseJSON')) {
                       if (xhr.responseJSON.hasOwnProperty('errors')) {
                           $.each(xhr.responseJSON.errors, function (key, value) {
                               if (key !== 'id') {
                                   $('#' + key).after('<span></span>');
                                   $('#' + key).parent().find('label').addClass('text-danger');
                                   $('#' + key).addClass('is-invalid');
                                   $.each(value, function (k, v) {
                                       $('#' + key).parent().find('span').addClass('text-danger').append('<p>' + v + '</p>');
                                   });
                               } else {
                                   $.each(value, function (k, v) {
                                       $('#image_form_message').append('<p>' + v + '</p>');
                                   });
                               }
                           });
                       }
                   }
               }

           });
           return false;
        });

        $(document).on('click', '.remove_image', function () {
           let id = $(this).data('id');
           console.log(id);

            $.confirm({
                title: 'Remove',
                content: 'Are You Sure to Remove This Image',
                buttons: {
                    confirm: {
                        btnClass: 'btn-red',
                        action: function() {
                            $.ajax({
                                method: 'get',
                                url: '{{ url('image/file/remove') }}/' + id,
                                cache: false,
                                success: function (result) {
                                    $.toaster({title: 'Warning', priority: 'warning', message: 'Image Removed'});
                                    console.log(result);
                                    currentPageUrl = '{{ url('get/image') }}';
                                    getImages(currentPageUrl);
                                },
                                error: function (xhr) {
                                    console.log(xhr);
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Close',
                        btnClass: 'btn-blue',
                        keys: ['enter']
                    }
                }
            });
           return false;
        });

    </script>

@endsection
