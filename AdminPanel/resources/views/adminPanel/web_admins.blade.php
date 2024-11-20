@extends('adminPanel/base')

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Web Admins</h6>
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
                                <h3 class="mb-0">Web Admins</h3>
                            </div>
                            <div class="col-lg-6 col-5 text-right">
                                <a class="btn btn-sm btn-primary" href="#" role="button" data-toggle="modal"
                                    data-target="#addWebAdminItem" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <div class="modal text-left" id="addWebAdminItem">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content justify-content-center">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h2 class="modal-title">New Admin</h2>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    &times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body model">
                                                <form method="post" action="{{ route('addWebAdminItem') }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label class="h4" for="email">Email</label>
                                                        <input type="email" class="form-control" id="email"
                                                            name="email" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="h4" for="password">Password</label>
                                                        <input type="password" class="form-control" id="password"
                                                            name="password" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="adminTypeSelect"><span class="h4">Admin
                                                                Type</span></label>
                                                        <select name="adminTypeSelect" id="adminTypeSelect"
                                                            class="custom-select">
                                                            <option selected value="0">Main Admin</option>
                                                            <option value="1">Teacher</option>
                                                        </select>
                                                    </div>
                                                    <button type="submit"
                                                        class="d-flex justify-content-center input-group btn btn-primary text-center">
                                                        Add Admin
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
                                    <th scope="col">Email</th>
                                    <th scope="col">Admin Type</th>
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
                                            <span class="h4">{{ $item['email'] }}</span>
                                        </td>
                                        <td>
                                            @if ($item['admin_type'] == 0)
                                                <span class="success_gradient">MAIN ADMIN</span>
                                            @else
                                                <span class="blue_gradient">TEACHER</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item['created'] }}
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="#" role="button"
                                                data-toggle="modal" data-target="#changePassword{{ $item['id'] }}"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a class="btn btn-sm btn-primary text-white"
                                                href="{{ route('deleteWebAdmin') }}?id={{ $item['id'] }}"
                                                role="button" data-toggle="tooltip" data-placement="top"
                                                title="Delete Admin">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                        <div class="modal text-left" id="changePassword{{ $item['id'] }}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content justify-content-center">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Change Password</h2>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body model">
                                                        <form method="post" action="{{ route('changeAdminPassword') }}">
                                                            @csrf
                                                            <input hidden type="text" class="form-control"
                                                                id="id" name="id" value="{{ $item['id'] }}"/>
                                                            <div class="form-group">
                                                                <label for="password{{ $item['id'] }}">New
                                                                    Password</label>
                                                                <input type="password" class="form-control"
                                                                    id="password{{ $item['id'] }}"
                                                                    name="password{{ $item['id'] }}" />
                                                            </div>
                                                            <button type="submit"
                                                                class="d-flex justify-content-center input-group btn btn-primary text-center">
                                                                Change
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
