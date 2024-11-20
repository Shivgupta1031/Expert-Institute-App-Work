@extends('adminPanel/base')

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Users</h6>
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
                                <h3 class="mb-0">Users</h3>
                            </div>
                            <div class="col-lg-6 col-5 text-right">
                                <a class="btn btn-sm btn-primary" href="{{ route('users') }}">
                                    <i class="fas fa-rotate"></i>
                                </a>
                                <a class="btn btn-sm btn-primary" href="#" role="button" data-toggle="modal"
                                    data-target="#searchUser" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-search"></i>
                                </a>
                                <div class="modal text-left" id="searchUser">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content justify-content-center">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h2 class="modal-title">Search User</h2>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    &times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body model">
                                                <form method="post" action="{{ route('searchUser') }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="term">Enter Search Term</label>
                                                        <input type="text" class="form-control" id="term"
                                                            name="term" />
                                                    </div>
                                                    <button type="submit" class="d-flex justify-content-center input-group btn btn-primary text-center">
                                                        Search User
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
                                    <th scope="col">Profile Pic</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">Account Status</th>
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
                                            @if ($item['account_status'] == 0)
                                                <span class="success_gradient">ACTIVE</span>
                                            @else
                                                <span class="error_gradient">BLOCKED</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="#" role="button"
                                                data-toggle="modal" data-target="#editUserItem{{ $item['id'] }}"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a class="btn btn-sm btn-primary text-white"
                                                href="{{ route('deleteUserItem') }}?id={{ $item['id'] }}" role="button"
                                                data-toggle="tooltip" data-placement="top" title="Delete Account">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            @if ($item['account_status'] == 0)
                                                <a class="btn btn-sm btn-primary text-white"
                                                    href="{{ route('blockUser') }}?id={{ $item['id'] }}"
                                                    role="button" data-toggle="tooltip" data-placement="top"
                                                    title="Block User">
                                                    <i class="fa fa-user-slash text-red"></i>
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn-primary text-white"
                                                    href="{{ route('unBlockUser') }}?id={{ $item['id'] }}"
                                                    role="button" data-toggle="tooltip" data-placement="top"
                                                    title="unBlock User">
                                                    <i class="fa fa-user-alt text-green"></i>
                                                </a>
                                            @endif
                                        </td>

                                        <div class="modal text-left" id="editUserItem{{ $item['id'] }}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content justify-content-center">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">View More Details</h2>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body model">
                                                        <div class="form-group">
                                                            <label for="id{{ $item['id'] }}">User ID</label>
                                                            <input disabled type="text" class="form-control"
                                                                id="id{{ $item['id'] }}" name="id{{ $item['id'] }}"
                                                                value="{{ $item['id'] }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="uid{{ $item['id'] }}">User UID</label>
                                                            <input disabled type="text" class="form-control"
                                                                id="uid{{ $item['id'] }}" name="uid{{ $item['id'] }}"
                                                                value="{{ $item['uid'] }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email{{ $item['id'] }}">Email</label>
                                                            <input disabled type="text" class="form-control"
                                                                id="email{{ $item['id'] }}"
                                                                name="email{{ $item['id'] }}"
                                                                value="{{ $item['email'] }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="state{{ $item['id'] }}">State</label>
                                                            <input disabled type="text" class="form-control"
                                                                id="state{{ $item['id'] }}"
                                                                name="state{{ $item['id'] }}"
                                                                value="{{ $item['state'] }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="last_active{{ $item['id'] }}">Last Active
                                                                Status</label>
                                                            <input disabled type="text" class="form-control"
                                                                id="last_active{{ $item['id'] }}"
                                                                name="last_active{{ $item['id'] }}"
                                                                value="{{ $item['last_active'] }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="country{{ $item['id'] }}">Country</label>
                                                            <input disabled type="text" class="form-control"
                                                                id="country{{ $item['id'] }}"
                                                                name="country{{ $item['id'] }}"
                                                                value="{{ $item['country'] }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="ip_address{{ $item['id'] }}">IP Address</label>
                                                            <input disabled type="text" class="form-control"
                                                                id="ip_address{{ $item['id'] }}"
                                                                name="ip_address{{ $item['id'] }}"
                                                                value="{{ $item['ip_address'] }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="device_id{{ $item['id'] }}">Device ID</label>
                                                            <input disabled type="text" class="form-control"
                                                                id="device_id{{ $item['id'] }}"
                                                                name="device_id{{ $item['id'] }}"
                                                                value="{{ $item['device_id'] }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="created{{ $item['id'] }}">Account Created
                                                                On</label>
                                                            <input disabled type="text" class="form-control"
                                                                id="created{{ $item['id'] }}"
                                                                name="created{{ $item['id'] }}"
                                                                value="{{ $item['created'] }}" />
                                                        </div>
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