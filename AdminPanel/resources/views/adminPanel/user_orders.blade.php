@extends('adminPanel/base')

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Users Orders</h6>
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
                                <h3 class="mb-0">Users Orders</h3>
                            </div>
                            <div class="col-lg-6 col-5 text-right">
                                <a class="btn btn-sm btn-primary" href="{{ route('user_orders') }}">
                                    <i class="fas fa-rotate"></i>
                                </a>
                                <a class="btn btn-sm btn-primary" href="#" role="button" data-toggle="modal"
                                    data-target="#searchUserOrderItem" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-search"></i>
                                </a>
                                <div class="modal text-left" id="searchUserOrderItem">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content justify-content-center">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h2 class="modal-title">Search User Order</h2>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    &times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body model">
                                                <form method="post" action="{{ route('searchUserOrderItem') }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="term">Enter Search Term</label>
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
                                    <th scope="col">Profile Pic</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Item</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Payment Method</th>
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
                                                <span>{{ $item['phone_number'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($item['type'] == config('enums.PAID_COURSE_ORDER'))
                                                <span class="success_gradient">Paid Course</span>
                                            @elseif ($item['type'] == config('enums.EBOOK_ORDER'))
                                                <span class="success_gradient">Ebook</span>
                                            @elseif ($item['type'] == config('enums.MOCK_TEST_CATEGORY_ORDER'))
                                                <span class="success_gradient">Mock Test Category</span>
                                            @elseif ($item['type'] == config('enums.MONTH_1_SUBSCRIPTION'))
                                                <span class="success_gradient">1 Month Subscription</span>
                                            @elseif ($item['type'] == config('enums.MONTH_3_SUBSCRIPTION'))
                                                <span class="success_gradient">3 Month Subscription</span>
                                            @elseif ($item['type'] == config('enums.MONTH_6_SUBSCRIPTION'))
                                                <span class="success_gradient">6 Month Subscription</span>
                                            @elseif ($item['type'] == config('enums.YEAR_1_SUBSCRIPTION'))
                                                <span class="success_gradient">1 Year Subscription</span>
                                            @elseif ($item['type'] == config('enums.PRODUCT_ORDER'))
                                                <span class="success_gradient">Digital Product</span>
                                            @elseif ($item['type'] == config('enums.MOCK_TEST_ORDER'))
                                                <span class="success_gradient">Mock Test</span>
                                            @endif
                                            <br>
                                            <br>
                                            <span class="h5">{{ $item['description'] }}</span>
                                        </td>
                                        <td>
                                            <span class="blue_gradient">{{ $item['price'] }} INR</span>
                                        </td>
                                        <td>
                                            @if ($item['payment_method'] == config('enums.RAZORPAY'))
                                                <span class="success_gradient">RazorPay</span>
                                            @elseif ($item['payment_method'] == config('enums.PHONE_PE'))
                                                <span class="success_gradient">PhonePe</span>
                                            @elseif ($item['payment_method'] == config('enums.MANUAL_PAYMENT'))
                                                <span class="success_gradient">Manual Payment</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="#" role="button"
                                                data-toggle="modal" data-target="#editUserOrdersItem{{ $item['id'] }}"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a class="btn btn-sm btn-primary text-white"
                                                href="{{ route('deleteUserOrdersItem') }}?id={{ $item['id'] }}"
                                                role="button" data-toggle="tooltip" data-placement="top"
                                                title="Delete User Order">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                        <div class="modal text-left" id="editUserOrdersItem{{ $item['id'] }}">
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
                                                            <label for="id{{ $item['id'] }}">ID</label>
                                                            <input disabled type="text" class="form-control"
                                                                id="id{{ $item['id'] }}" name="id{{ $item['id'] }}"
                                                                value="{{ $item['id'] }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label
                                                                for="transaction_details{{ $item['id'] }}">Transaction
                                                                Details</label>
                                                            <input disabled type="text" class="form-control"
                                                                id="transaction_details{{ $item['id'] }}"
                                                                name="transaction_details{{ $item['id'] }}"
                                                                value="{{ $item['transaction_details'] }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="coupan{{ $item['id'] }}">Coupan Used</label>
                                                            <input disabled type="text" class="form-control"
                                                                id="coupan{{ $item['id'] }}"
                                                                name="coupan{{ $item['id'] }}"
                                                                value="{{ $item['coupan'] }}" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="created{{ $item['id'] }}">Created
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
