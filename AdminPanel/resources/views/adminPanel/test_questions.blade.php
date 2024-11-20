@extends('adminPanel/base')

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Test Questions</h6>
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
                                <h3 class="mb-0">Test Questions</h3>
                            </div>
                            <div class="col-lg-6 col-5 text-right">
                                <a class="btn btn-sm btn-primary" href="{{ route('test_questions') }}">
                                    <i class="fas fa-rotate"></i>
                                </a>
                                <a class="btn btn-sm btn-primary" href="#" role="button" data-toggle="modal"
                                    data-target="#searchTestQuestionsItem" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-search"></i>
                                </a>
                                <a class="btn btn-sm btn-primary" href="#" role="button" data-toggle="modal"
                                    data-target="#addTestQuestionsItem" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-plus"></i>
                                </a>

                                <div class="modal text-left" id="addTestQuestionsItem">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content justify-content-center">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h2 class="modal-title">Add Test Question</h2>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    &times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body model">
                                                <form method="post" action="{{ route('addTestQuestionsItem') }}"
                                                    id="img-upload-form" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="mock_test_id"><span class="h4">Mock Test</span></label>
                                                        <select name="mock_test_id" id="mock_test_id"
                                                            class="custom-select">
                                                            @forelse($mockTests as $item)
                                                                <option value="{{ $item['id'] }}">{{ $loop->index + 1 }}.
                                                                    {{ $item['title'] }}</option>
                                                            @empty
                                                                <option value="">No Mock Test Found</option>
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="h4" for="question">Question</label>
                                                        <input type="text" class="form-control" id="question"
                                                            name="question" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="h4" for="opt_1">Option 1</label>
                                                        <input type="text" class="form-control" id="opt_1"
                                                            name="opt_1" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="h4" for="opt_2">Option 2</label>
                                                        <input type="text" class="form-control" id="opt_2"
                                                            name="opt_2" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="h4" for="opt_3">Option 3</label>
                                                        <input type="text" class="form-control" id="opt_3"
                                                            name="opt_3" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="h4" for="opt_4">Option 4</label>
                                                        <input type="text" class="form-control" id="opt_4"
                                                            name="opt_4" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="h4" for="correct_option_no">Correct Option No.</label>
                                                        <input type="text" class="form-control" id="correct_option_no"
                                                            name="correct_option_no" />
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

                                <div class="modal text-left" id="searchTestQuestionsItem">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content justify-content-center">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h2 class="modal-title">Search Test Question</h2>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    &times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body model">
                                                <form method="post" action="{{ route('searchTestQuestionsItem') }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="term">Enter Search Term</label>
                                                        <input type="text" class="form-control" id="term"
                                                            name="term" />
                                                    </div>
                                                    <button type="submit" class="d-flex justify-content-center input-group btn btn-primary text-center">
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
                                    <th scope="col">Question</th>
                                    <th scope="col">Mock Test</th>
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
                                                <span>{{ $item['question'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span>{{ $item['mock_test'] }}</span>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="#" role="button"
                                                data-toggle="modal"
                                                data-target="#editTestQuestionsItem{{ $item['id'] }}"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a class="btn btn-sm btn-primary text-white"
                                                href="{{ route('deleteTestQuestionsItem') }}?id={{ $item['id'] }}"
                                                role="button" data-toggle="tooltip" data-placement="top"
                                                title="Delete Test Question">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>

                                        <div class="modal text-left" id="editTestQuestionsItem{{ $item['id'] }}">
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
                                                        <form method="post"
                                                            action="{{ route('editTestQuestionsItem') }}">
                                                            @csrf
                                                            <input hidden type="text" class="form-control"
                                                                id="id" name="id"
                                                                value="{{ $item['id'] }}" />
                                                            <div class="form-group">
                                                                <label for="id{{ $item['id'] }}">Test Question ID</label>
                                                                <input disabled type="text" class="form-control"
                                                                    id="id{{ $item['id'] }}"
                                                                    name="id{{ $item['id'] }}"
                                                                    value="{{ $item['id'] }}" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="question{{ $item['id'] }}">Question</label>
                                                                <input type="text" class="form-control"
                                                                    id="question{{ $item['id'] }}"
                                                                    name="question{{ $item['id'] }}"
                                                                    value="{{ $item['question'] }}" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="opt_1{{ $item['id'] }}">Option 1</label>
                                                                <input type="text" class="form-control"
                                                                    id="opt_1{{ $item['id'] }}"
                                                                    name="opt_1{{ $item['id'] }}"
                                                                    value="{{ $item['opt_1'] }}" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="opt_2{{ $item['id'] }}">Option 2</label>
                                                                <input type="text" class="form-control"
                                                                    id="opt_2{{ $item['id'] }}"
                                                                    name="opt_2{{ $item['id'] }}"
                                                                    value="{{ $item['opt_2'] }}" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="opt_3{{ $item['id'] }}">Option 3</label>
                                                                <input type="text" class="form-control"
                                                                    id="opt_3{{ $item['id'] }}"
                                                                    name="opt_3{{ $item['id'] }}"
                                                                    value="{{ $item['opt_3'] }}" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="opt_4{{ $item['id'] }}">Option 4</label>
                                                                <input type="text" class="form-control"
                                                                    id="opt_4{{ $item['id'] }}"
                                                                    name="opt_4{{ $item['id'] }}"
                                                                    value="{{ $item['opt_4'] }}" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="correct_option_no{{ $item['id'] }}">Correct Option No.</label>
                                                                <input type="text" class="form-control"
                                                                    id="correct_option_no{{ $item['id'] }}"
                                                                    name="correct_option_no{{ $item['id'] }}"
                                                                    value="{{ $item['correct_option_no'] }}" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="mock_test_id{{ $item['id'] }}"><span
                                                                        class="h4">Mock Test</span></label>
                                                                <select name="mock_test_id{{ $item['id'] }}"
                                                                    id="mock_test_id{{ $item['id'] }}"
                                                                    class="custom-select">
                                                                    @forelse($mockTests as $catItem)
                                                                        @if ($catItem['id'] == $item['mock_test_id'])
                                                                            <option selected value="{{ $catItem['id'] }}">
                                                                                {{ $loop->index + 1 }}.
                                                                                {{ $catItem['title'] }}</option>
                                                                        @else
                                                                            <option value="{{ $catItem['id'] }}">
                                                                                {{ $loop->index + 1 }}.
                                                                                {{ $catItem['title'] }}</option>
                                                                        @endif
                                                                    @empty
                                                                        <option value="">No Mock Test Found</option>
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
