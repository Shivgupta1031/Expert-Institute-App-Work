@extends('adminPanel/base')

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Ebooks</h6>
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
                                <h3 class="mb-0">Ebooks</h3>
                            </div>
                            <div class="col-lg-6 col-5 text-right">
                                <a class="btn btn-sm btn-primary" href="#" role="button" data-toggle="modal"
                                    data-target="#addEbookItem" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <div class="modal text-left" id="addEbookItem">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content justify-content-center">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h2 class="modal-title">Add Ebook</h2>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    &times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body model">
                                                <form method="post" action="{{ route('addEbookItem') }}"
                                                    id="img-upload-form" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label class="h4" for="title">Title</label>
                                                        <input type="text" class="form-control" id="title"
                                                            name="title" />
                                                    </div>
                                                    <label class="h4">Ebook Image</label>
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
                                                    <label class="h4">Ebook File (PDF)</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file" accept=".pdf"
                                                            name="file" id="image2" />
                                                        <label class="custom-file-label" id="imageName2"
                                                            for="image2">Choose File</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="h4" for="url">Price</label>
                                                        <input type="text" class="form-control" id="price"
                                                            name="price" />
                                                    </div>
                                                    <button type="submit"
                                                        class="d-flex justify-content-center input-group btn btn-primary text-center">
                                                        Add Ebook
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
                                    <th scope="col">Price</th>
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
                                            <span class="blue_gradient">{{ $item['price'] }} INR</span>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="#" role="button"
                                                data-toggle="modal" data-target="#editEbookItem{{ $item['id'] }}"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a class="btn btn-sm btn-primary text-white"
                                                href="{{ route('deleteEbookItem') }}?id={{ $item['id'] }}"
                                                role="button" data-toggle="tooltip" data-placement="top"
                                                title="Delete Ebook">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>

                                        <div class="modal text-left" id="editEbookItem{{ $item['id'] }}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content justify-content-center">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Edit Details</h2>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body model">
                                                        <form method="post" action="{{ route('editEbookItem') }}">
                                                            @csrf
                                                            <input hidden type="text" class="form-control"
                                                                id="id" name="id"
                                                                value="{{ $item['id'] }}" />
                                                            <div class="form-group">
                                                                <label for="id{{ $item['id'] }}">Ebook ID</label>
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
                                                            <div class="form-group">
                                                                <label for="file{{ $item['id'] }}">Ebook File</label>
                                                                <input disabled type="text" class="form-control"
                                                                    id="file{{ $item['id'] }}"
                                                                    name="file{{ $item['id'] }}"
                                                                    value="{{ $item['file'] }}" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="price{{ $item['id'] }}">Price</label>
                                                                <input type="text" class="form-control"
                                                                    id="price{{ $item['id'] }}"
                                                                    name="price{{ $item['id'] }}"
                                                                    value="{{ $item['price'] }}" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="created{{ $item['id'] }}">Created On</label>
                                                                <input disabled type="text" class="form-control"
                                                                    id="created{{ $item['id'] }}"
                                                                    name="created{{ $item['id'] }}"
                                                                    value="{{ $item['created'] }}" />
                                                            </div>
                                                            <button type="submit"
                                                                class="d-flex justify-content-center input-group btn btn-primary text-center">
                                                                Update
                                                            </button>
                                                        </form>
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
