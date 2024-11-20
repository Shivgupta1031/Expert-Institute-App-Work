@extends('adminPanel/base')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.23.0/ui/trumbowyg.min.css"
        integrity="sha512-iw/TO6rC/bRmSOiXlanoUCVdNrnJBCOufp2s3vhTPyP1Z0CtTSBNbEd5wIo8VJanpONGJSyPOZ5ZRjZ/ojmc7g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Videos</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0">
                        <div id="noticeAlert">
                            @if (\Session::has('message'))
                                @if (\Session::has('success') && \Session::get('success'))
                                    <div class="alert-success alert alert-dismissible fade show w-100 mr-3"
                                        data-auto-dismiss="3000" role="alert">
                                        <span class="h4 text-white"> <i
                                                class="fas fa-check mr-2"></i>{!! \Session::get('message') !!}</span>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    </div>
                                @else
                                    <div class="alert-danger alert alert-dismissible fade show w-100 mr-3"
                                        data-auto-dismiss="3000" role="alert">
                                        <span class="h4 text-white"> <i
                                                class="fas fa-check mr-2"></i>{!! \Session::get('message') !!}</span>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Videos</h3>
                            </div>
                            <div class="col-lg-6 col-5 text-right">
                                <a class="btn btn-sm btn-primary" href="#" role="button" data-toggle="modal"
                                    data-target="#addVideosItem" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <div class="modal text-left" id="addVideosItem">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content justify-content-center">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h2 class="modal-title">Add Video</h2>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    &times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body model">
                                                <form method="post" action="{{ route('addVideosItem') }}"
                                                    id="img-upload-form" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label class="h4" for="title">Title</label>
                                                        <input type="text" class="form-control" id="title"
                                                            name="title" />
                                                    </div>
                                                    <label class="h4">Video Image</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file" accept="image/*"
                                                            name="image" id="image" />
                                                        <label class="custom-file-label" id="imageName"
                                                            for="image">Choose
                                                            File</label>
                                                    </div>
                                                    <div class="active-pink-3 active-pink-4 mb-2">
                                                        <input type="text" class="form-control" id="imageUrl"
                                                            name="imageUrl" placeholder="Or Enter File Url" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="h4" for="url">Video Link</label>
                                                        <input type="text" class="form-control" id="video_link"
                                                            name="video_link" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <textarea class="form-control editor" id="description" name="description"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="video_type"><span class="h4">Video
                                                                Type</span></label>
                                                        <select name="video_type" id="video_type" class="custom-select">
                                                            <option selected value="0">Recorded</option>
                                                            <option value="1">Live</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="paid"><span class="h4">Is Paid</span></label>
                                                        <select name="paid" id="paid" class="custom-select">
                                                            <option selected value="1">PAID</option>
                                                            <option value="0">FREE</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group" id="course_id_Field">
                                                        <label for="course_id"><span class="h4">Select
                                                                Course</span></label>
                                                        <select name="course_id" id="course_id" class="custom-select">
                                                            <option selected value="0">Free</option>
                                                            @forelse($course as $item)
                                                                <option value="{{ $item['id'] }}">{{ $loop->index + 1 }}.
                                                                    {{ $item['title'] }}</option>
                                                            @empty
                                                                <option value="0">No Course Found</option>
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                    <div class="form-group" id="category_id_Field">
                                                        <label for="category_id"><span class="h4">Select Video
                                                                Category</span></label>
                                                        <select name="category_id" id="category_id"
                                                            class="custom-select">
                                                            @forelse($categories as $item)
                                                                <option value="{{ $item['id'] }}">{{ $loop->index + 1 }}.
                                                                    {{ $item['category'] }}</option>
                                                            @empty
                                                                <option value="0">No Categories Found</option>
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                    <button type="submit"
                                                        class="d-flex justify-content-center input-group btn btn-primary text-center">
                                                        Add Video
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" id="noticeTable">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">S.no</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Video Type</th>
                                    <th scope="col">PAID</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                    <tr>
                                        <td>
                                            <span>{{ $loop->index + 1 }}</span>
                                        </td>
                                        <td>
                                            @if (strlen($item['image']) == 0)
                                                <img class="img-fluid" src="{{ asset('images/user.png') }}"
                                                    width="60px">
                                            @else
                                                <img class="img-fluid rounded" src="{{ $item['image'] }}" width="120px">
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-wrap h5" style="width: 200px">
                                                <span>{{ $item['title'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($item['video_type'] == 0)
                                                <span class="success_gradient">RECORDED</span>
                                            @else
                                                <span class="blue_gradient">LIVE</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item['paid'] == 1)
                                                <span class="success_gradient">PAID</span>
                                            @else
                                                <span class="blue_gradient">FREE</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="#" role="button"
                                                data-toggle="modal" data-target="#editVideoItem{{ $item['id'] }}"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a class="btn btn-sm btn-primary text-white"
                                                href="{{ route('deleteVideosItem') }}?id={{ $item['id'] }}"
                                                role="button" data-toggle="tooltip" data-placement="top"
                                                title="Delete Video">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>

                                        <div class="modal text-left" id="editVideoItem{{ $item['id'] }}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content justify-content-center">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">View Details</h2>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body model">
                                                        <form id="editDataForm{{ $item['id'] }}" method="post" action="{{ route('editVideoItem') }}">
                                                            @csrf
                                                            <input hidden type="text" class="form-control"
                                                                id="id" name="id"
                                                                value="{{ $item['id'] }}" />
                                                            <div class="form-group">
                                                                <label for="id{{ $item['id'] }}">Video ID</label>
                                                                <input disabled type="text" class="form-control"
                                                                    id="id{{ $item['id'] }}"
                                                                    name="id{{ $item['id'] }}"
                                                                    value="{{ $item['id'] }}" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="title{{ $item['id'] }}">Title</label>
                                                                <input type="text" class="form-control"
                                                                    id="title{{ $item['id'] }}"
                                                                    name="title{{ $item['id'] }}"
                                                                    value="{{ $item['title'] }}" />
                                                            </div>
                                                            <input hidden type="text" class="form-control"
                                                                id="description{{ $item['id'] }}"
                                                                name="description{{ $item['id'] }}" />
                                                            <div class="form-group">
                                                                <label
                                                                    for="descrip{{ $item['id'] }}">Description</label>
                                                                <textarea class="form-control editor" id="descrip{{ $item['id'] }}">{{ $item['description'] }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="video_link{{ $item['id'] }}">Video
                                                                    Link</label>
                                                                <input type="text" class="form-control"
                                                                    id="video_link{{ $item['id'] }}"
                                                                    name="video_link{{ $item['id'] }}"
                                                                    value="{{ $item['video_link'] }}" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="video_type{{ $item['id'] }}"><span
                                                                        class="h4">Video type</span></label>
                                                                <select name="video_type{{ $item['id'] }}"
                                                                    id="video_type{{ $item['id'] }}"
                                                                    class="custom-select">
                                                                    @if ($item['video_type'] == 0)
                                                                        <option selected value="0">RECORDED</option>
                                                                        <option value="1">LIVE</option>
                                                                    @else
                                                                        <option value="0">RECORDED</option>
                                                                        <option selected value="1">LIVE</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="paid{{ $item['id'] }}"><span
                                                                        class="h4">Paid</span></label>
                                                                <select name="paid{{ $item['id'] }}"
                                                                    id="paid{{ $item['id'] }}"
                                                                    class="custom-select">
                                                                    @if ($item['paid'] == 0)
                                                                        <option selected value="0">FREE</option>
                                                                        <option value="1">PAID</option>
                                                                    @else
                                                                        <option value="0">FREE</option>
                                                                        <option selected value="1">PAID</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="course_id{{ $item['id'] }}"><span
                                                                        class="h4">Course</span></label>
                                                                <select name="course_id{{ $item['id'] }}"
                                                                    id="course_id{{ $item['id'] }}"
                                                                    class="custom-select">
																	@if ($item['course_id'] == 0)
																	<option selected value="0">Free</option>
																@else
                                                                        <option value="0">Free</option>

                                                                        @endif
                                                                    @forelse($course as $catItem)
                                                                        @if ($catItem['id'] == $item['course_id'])
                                                                            <option selected value="{{ $catItem['id'] }}">
                                                                                {{ $loop->index + 1 }}.
                                                                                {{ $catItem['title'] }}</option>
                                                                        @else
                                                                            <option value="{{ $catItem['id'] }}">
                                                                                {{ $loop->index + 1 }}.
                                                                                {{ $catItem['title'] }}</option>
                                                                        @endif
                                                                    @empty
                                                                        <option value="">No Courses Found</option>
                                                                    @endforelse
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="category_id{{ $item['id'] }}"><span
                                                                        class="h4">Video Category</span></label>
                                                                <select name="category_id{{ $item['id'] }}"
                                                                    id="category_id{{ $item['id'] }}"
                                                                    class="custom-select">
                                                                    @forelse($categories as $catItem)
                                                                        @if ($catItem['id'] == $item['category_id'])
                                                                            <option selected value="{{ $catItem['id'] }}">
                                                                                {{ $loop->index + 1 }}.
                                                                                {{ $catItem['category'] }}</option>
                                                                        @else
                                                                            <option value="{{ $catItem['id'] }}">
                                                                                {{ $loop->index + 1 }}.
                                                                                {{ $catItem['category'] }}</option>
                                                                        @endif
                                                                    @empty
                                                                        <option value="">No Category Found</option>
                                                                    @endforelse
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="created{{ $item['id'] }}">Created On</label>
                                                                <input disabled type="text" class="form-control"
                                                                    id="created{{ $item['id'] }}"
                                                                    name="created{{ $item['id'] }}"
                                                                    value="{{ $item['created'] }}" />
                                                            </div>
                                                        </form>
                                                        <button onclick="editItem('{{ $item['id'] }}')"
                                                            class="d-flex justify-content-center input-group btn btn-primary text-center">
                                                            Update
                                                        </button>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>Nothing Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div id="noticePages" class="card-footer border-0">
                        <div class="row align-items-center align-middle">
                            <div class="col">
                                {{ $data->links('vendor/pagination/bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('adminPanel.footer')

    </div>

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"
        integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g=="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.js"
        integrity="sha512-RTxmGPtGtFBja+6BCvELEfuUdzlPcgf5TZ7qOVRmDfI9fDdX2f1IwBq+ChiELfWt72WY34n0Ti1oo2Q3cWn+kw=="
        crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.23.0/trumbowyg.min.js"
        integrity="sha512-sffB9/tXFFTwradcJHhojkhmrCj0hWeaz8M05Aaap5/vlYBfLx5Y7woKi6y0NrqVNgben6OIANTGGlojPTQGEw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(function() {
            $('.editor').trumbowyg();
        });

        $('#video_type').on('change', function() {
            if (this.value == 1) {
                $('#category_id_Field').hide();
            } else {
                $('#category_id_Field').show();
            }
        });

        function editItem(id) {
            var descValue = document.getElementById('descrip' + id).value;
            var description = document.getElementById('description' + id);
            description.setAttribute('value', descValue);
            var form = document.getElementById('editDataForm' + id);
            form.submit();
        }
    </script>
@endsection
