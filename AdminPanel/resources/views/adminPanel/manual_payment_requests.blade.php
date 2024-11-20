@extends('adminPanel/base')

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Manual Payment Requests</h6>
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
                                <h3 class="mb-0">Manual Payment Requests</h3>
                            </div>
                            <div class="col-lg-6 col-5 text-right">

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" id="noticeTable">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">S.no</th>
                                    <th scope="col">Profile Pic</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Status</th>
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
                                            @if (strlen($item['profile_pic']) == 0)
                                                <img class="img-fluid" src="{{ asset('images/user.png') }}" width="60px">
                                            @else
                                                <img class="img-fluid rounded" src="{{ $item['profile_pic'] }}"
                                                    width="100px">
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-wrap h4" style="width: 100px">
                                                <span>{{ $item['username'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $item['phone_number'] }}
                                        </td>
                                        <td>
                                            {{ $item['amount'] }}
                                        </td>
                                        <td>
                                            @if ($item['status'] == 2)
                                                <span class="success_gradient">CANCELLED</span>
                                            @elseif ($item['status'] == 1)
                                                <span class="success_gradient">COMPLETED</span>
                                            @else
                                                <span class="error_gradient">PENDING</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="#" role="button"
                                                data-toggle="modal" data-target="#editManualRequestsItem{{ $item['id'] }}"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a class="btn btn-sm btn-primary text-white"
                                                href="{{ route('deleteManualRequestsItem') }}?id={{ $item['id'] }}"
                                                role="button" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                        <div class="modal text-left" id="editManualRequestsItem{{ $item['id'] }}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content justify-content-center">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Edit</h2>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body model">
                                                        <form method="post"
                                                            action="{{ route('editManualRequestsItem') }}">
                                                            @csrf
                                                            <input hidden type="text" class="form-control" id="id"
                                                                name="id" value="{{ $item['id'] }}" />

                                                            <div class="form-group">
                                                                <label for="transaction_id{{ $item['id'] }}">Transaction ID</label>
                                                                <input disabled type="text" class="form-control"
                                                                    id="transaction_id{{ $item['id'] }}"
                                                                    name="transaction_id{{ $item['id'] }}"
                                                                    value="{{ $item['transaction_id'] }}" />
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="status{{ $item['id'] }}"><span
                                                                        class="h4">Status</span></label>
                                                                <select name="status{{ $item['id'] }}"
                                                                    class="custom-select">
                                                                    @if ($item['status'] == 2)
                                                                        <option selected value="2">CANCELLED</option>
                                                                    @else
                                                                        <option value="2">CANCELLED</option>
                                                                    @endif
                                                                    @if ($item['status'] == 1)
                                                                        <option selected value="1">COMPLETED</option>
                                                                    @else
                                                                        <option value="1">COMPLETED</option>
                                                                    @endif
                                                                    @if ($item['status'] == 0)
                                                                        <option selected value="0">PENDING</option>
                                                                    @else
                                                                        <option value="0">PENDING</option>
                                                                    @endif
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
                                {{ $data->appends(request()->query())->links('vendor/pagination/bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('adminPanel.footer')

    </div>

@endsection
