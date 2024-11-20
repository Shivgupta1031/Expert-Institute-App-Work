@extends('adminPanel/base')

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Mock Tests</h6>
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
                                <h3 class="mb-0">Mock Tests</h3>
                            </div>
                            <div class="col-lg-6 col-5 text-right">
                                <a class="btn btn-sm btn-primary" href="{{ route('mock_tests') }}">
                                    <i class="fas fa-rotate"></i>
                                </a>
                                <a class="btn btn-sm btn-primary" href="#" role="button" data-toggle="modal"
                                    data-target="#searchMockTest" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-search"></i>
                                </a>
                                <a class="btn btn-sm btn-primary" href="#" role="button" data-toggle="modal"
                                    data-target="#addMockTestItem" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <div class="modal text-left" id="addMockTestItem">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content justify-content-center">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h2 class="modal-title">Add Test</h2>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    &times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body model">
                                                <form method="post" action="{{ route('addMockTestItem') }}"
                                                    id="img-upload-form" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label class="h4" for="title">Title</label>
                                                        <input type="text" class="form-control" id="title"
                                                            name="title" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="type"><span class="h4">Test Type</span></label>
                                                        <select name="type" id="type" class="custom-select">
                                                            <option selected value="0">Category</option>
                                                            <option value="1">Paid Course</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group" id="test_category_id_Field">
                                                        <label for="test_category_id"><span class="h4">Select
                                                                Category</span></label>
                                                        <select name="test_category_id" id="test_category_id"
                                                            class="custom-select">
                                                            @forelse($categories as $item)
                                                                <option value="{{ $item['id'] }}">
                                                                    {{ $loop->index + 1 }}.
                                                                    {{ $item['title'] }}</option>
                                                            @empty
                                                                <option value="">No Category Found</option>
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                    <div class="form-group" id="course_id_Field">
                                                        <label for="course_id"><span class="h4">Select
                                                                Course</span></label>
                                                        <select name="course_id" id="course_id" class="custom-select">
                                                            @forelse($course as $item)
                                                                <option value="{{ $item['id'] }}">
                                                                    {{ $loop->index + 1 }}.
                                                                    {{ $item['title'] }}</option>
                                                            @empty
                                                                <option value="">No Course Found</option>
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="h4" for="test_time">Test Time (Minutes)</label>
                                                        <input type="text" class="form-control" id="test_time"
                                                            name="test_time" />
                                                    </div>
                                                    <br>
                                                    <button type="submit"
                                                        class="d-flex justify-content-center input-group btn btn-primary text-center">
                                                        Add
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


                                <div class="modal text-left" id="searchMockTest">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content justify-content-center">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h2 class="modal-title">Search Mock Test</h2>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    &times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body model">
                                                <form method="post" action="{{ route('searchMockTestItem') }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="term">Enter Mock Test Category</label>
                                                        <input type="text" class="form-control" id="term"
                                                            name="term" />
                                                    </div>
                                                    <button type="submit"
                                                        class="d-flex justify-content-center input-group btn btn-primary text-center">
                                                        Search
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
                                    <th scope="col">Title</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Test Time</th>
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
                                            <div class="text-wrap h5" style="width: 200px">
                                                <span>{{ $item['title'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($item['type'] == 0)
                                                <span class="success_gradient">Category</span>
                                            @else
                                                <span class="error_gradient">Paid Course</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="blue_gradient">{{ $item['test_time'] }} Mins</span>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="#" role="button"
                                                data-toggle="modal" data-target="#editMockTestItem{{ $item['id'] }}"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a class="btn btn-sm btn-primary text-white"
                                                href="{{ route('deleteMockTestItem') }}?id={{ $item['id'] }}"
                                                role="button" data-toggle="tooltip" data-placement="top"
                                                title="Delete Mock Test">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>

                                        <div class="modal text-left" id="editMockTestItem{{ $item['id'] }}">
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
                                                        <form method="post" action="{{ route('editMockTestItem') }}">
                                                            @csrf
                                                            <input hidden type="text" class="form-control"
                                                                id="id" name="id"
                                                                value="{{ $item['id'] }}" />
                                                            <div class="form-group">
                                                                <label for="id{{ $item['id'] }}">Mock Test ID</label>
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
                                                                <label for="type{{ $item['id'] }}"><span
                                                                        class="h4">Test
                                                                        Type</span></label>
                                                                <select disabled name="type{{ $item['id'] }}"
                                                                    id="type{{ $item['id'] }}" class="custom-select">
                                                                    @if ($item['type'] == 0)
                                                                        <option selected value="0">Category</option>
                                                                        <option value="1">Paid Course</option>
                                                                    @else
                                                                        <option value="0">Category</option>
                                                                        <option selected value="1">Paid Course
                                                                        </option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            @if ($item['type'] == 0)
                                                                <div class="form-group">
                                                                    <label for="test_category_id{{ $item['id'] }}"><span
                                                                            class="h4">Category</span></label>
                                                                    <select name="test_category_id{{ $item['id'] }}"
                                                                        id="test_category_id{{ $item['id'] }}"
                                                                        class="custom-select">
                                                                        @forelse($categories as $catItem)
                                                                            @if ($catItem['id'] == $item['test_category_id'])
                                                                                <option selected
                                                                                    value="{{ $catItem['id'] }}">
                                                                                    {{ $loop->index + 1 }}.
                                                                                    {{ $catItem['title'] }}</option>
                                                                            @else
                                                                                <option value="{{ $catItem['id'] }}">
                                                                                    {{ $loop->index + 1 }}.
                                                                                    {{ $catItem['title'] }}</option>
                                                                            @endif
                                                                        @empty
                                                                            <option value="">No Category Found
                                                                            </option>
                                                                        @endforelse
                                                                    </select>
                                                                </div>
                                                            @else
                                                                <div class="form-group">
                                                                    <label for="course_id{{ $item['id'] }}"><span
                                                                            class="h4">Category</span></label>
                                                                    <select name="course_id{{ $item['id'] }}"
                                                                        id="course_id{{ $item['id'] }}"
                                                                        class="custom-select">
                                                                        @forelse($course as $catItem)
                                                                            @if ($catItem['id'] == $item['course_id'])
                                                                                <option selected
                                                                                    value="{{ $catItem['id'] }}">
                                                                                    {{ $loop->index + 1 }}.
                                                                                    {{ $catItem['title'] }}</option>
                                                                            @else
                                                                                <option value="{{ $catItem['id'] }}">
                                                                                    {{ $loop->index + 1 }}.
                                                                                    {{ $catItem['title'] }}</option>
                                                                            @endif
                                                                        @empty
                                                                            <option value="">No Course Found</option>
                                                                        @endforelse
                                                                    </select>
                                                                </div>
                                                            @endif
                                                            <div class="form-group">
                                                                <label for="test_time{{ $item['id'] }}">Test Time
                                                                    (Mins)</label>
                                                                <input type="text" class="form-control"
                                                                    id="test_time{{ $item['id'] }}"
                                                                    name="test_time{{ $item['id'] }}"
                                                                    value="{{ $item['test_time'] }}" />
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

@section('js')
    <script>
        $('#course_id_Field').hide();

        $('#type').on('change', function() {
            if (this.value == 1) {
                $('#course_id_Field').show();
                $('#test_category_id_Field').hide();
            } else {
                $('#test_category_id_Field').show();
                $('#course_id_Field').hide();
            }
        });
    </script>
@endsection
