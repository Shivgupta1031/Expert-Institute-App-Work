@extends('adminPanel/base')

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Send Notification</h6>
                    </div>
                </div>
                <div id="noticeAlert">
                    @if (\Session::has('message'))
                        @if (\Session::has('success') && \Session::get('success'))
                            <div class="alert-success alert alert-dismissible fade show w-100 mr-3" data-auto-dismiss="3000"
                                role="alert">
                                <span class="h4 text-white"> <i class="fas fa-check mr-2"></i>{!! \Session::get('message') !!}</span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                        @else
                            <div class="alert-danger alert alert-dismissible fade show w-100 mr-3" data-auto-dismiss="3000"
                                role="alert">
                                <span class="h4 text-white"> <i class="fas fa-check mr-2"></i>{!! \Session::get('message') !!}</span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 mt-7">
                <form method="post" action="{{ route('sendNotification') }}"
                    id="img-upload-form" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-2">
                        <input type="text" name="title" class="form-control" placeholder="Enter Notice Title">
                    </div>
                    <div class="input-group mb-2">
                        <input type="text" name="message" class="form-control" placeholder="Enter Message">
                    </div>
                    <div class="input-group mb-2">
                        <input type="text" name="clickUrl" class="form-control" placeholder="Enter Notice Click Url">
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file" accept="image/*" name="image" id="image" />
                        <label class="custom-file-label" id="imageName" for="image">Notification Image</label>
                    </div>
                    <div class="active-pink-3 active-pink-4 mb-2">
                        <input type="text" class="form-control" id="imageUrl" name="imageUrl"
                            placeholder="Or Enter Notification Image Url" />
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Send</button>
                </form>
            </div>
        </div>

        @include('adminPanel.footer')

    </div>

@endsection
