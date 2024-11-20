@extends('adminPanel/base')

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Banner</h6>
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
                                <h3 class="mb-0">Banner</h3>
                            </div>
                            <div class="col-lg-6 col-5 text-right">
                                <a class="btn btn-sm btn-primary" href="#" role="button" data-toggle="modal"
                                    data-target="#addBannerItem" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <div class="modal text-left" id="addBannerItem">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content justify-content-center">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h2 class="modal-title">New Banner</h2>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    &times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body model">
                                                <form method="post" action="{{ route('addBannerItem') }}"
                                                    id="img-upload-form" enctype="multipart/form-data">
                                                    @csrf
                                                    <label class="h4">Banner Image</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file" accept="image/*"
                                                            name="image" id="image" />
                                                        <label class="custom-file-label" id="imageName"
                                                            for="image">Choose File</label>
                                                    </div>
                                                    <div class="active-pink-3 active-pink-4 mb-2">
                                                        <input type="text" class="form-control" id="imageUrl"
                                                            name="imageUrl" placeholder="Or Enter File Url" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="bannerTypeSelect"><span class="h4">Click
                                                                Action</span></label>
                                                        <select name="bannerTypeSelect" id="bannerTypeSelect"
                                                            class="custom-select">
                                                            <option selected value="0">Open Website</option>
                                                            <option value="1">Open Course</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group" id="courseSelectContainer"
                                                        style="display: none;">
                                                        <label for="courseSelect"><span class="h4">Select
                                                                Course</span></label>
                                                        <select name="courseSelect" id="courseSelect" class="custom-select">
                                                            <option selected disabled>Select a course</option>
                                                            @foreach ($courses as $c)
                                                                <option value="{{ $c->id }}">{{ $c->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="form-group" id="urlCont">
                                                        <label class="h4" for="url">Url</label>
                                                        <input type="text" class="form-control" id="url"
                                                            name="url" />
                                                    </div>

                                                    <button type="submit"
                                                        class="d-flex justify-content-center input-group btn btn-primary text-center">
                                                        Add Banner
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
                                    <th scope="col">ID</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Url</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Created On</th>
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
                                            <img class="img-fluid rounded" src="{{ $item['image'] }}" width="180px">
                                        </td>
                                        <td>
                                            {{ $item['url'] }}
                                        </td>
                                        <td>
                                            @if ($item['type'] == 0)
                                                <span class="success_gradient">WEBSITE</span>
                                            @else
                                                <span class="error_gradient">COURSE</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item['created'] }}
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary text-white"
                                                href="{{ route('deleteBannerItem') }}?id={{ $item['id'] }}"
                                                role="button" data-toggle="tooltip" data-placement="top"
                                                title="Delete Banner">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const bannerTypeSelect = document.getElementById("bannerTypeSelect");
            const courseSelectContainer = document.getElementById("courseSelectContainer");
            const courseSelect = document.getElementById("courseSelect");
            const urlInput = document.getElementById("url");
            const urlCont = document.getElementById("urlCont");

            bannerTypeSelect.addEventListener("change", function() {
                if (this.value === "1") {
                    courseSelectContainer.style.display = "block";
                    urlCont.style.display = "none";
                } else {
                    courseSelectContainer.style.display = "none";
                    urlCont.style.display = "block";
                }
            });

            courseSelect.addEventListener("change", function() {
                const selectedCourse = courseSelect.options[courseSelect.selectedIndex].value;
                if (selectedCourse) {
                    urlInput.value = `${selectedCourse}`;
                }
            });
        });
    </script>
@endsection
