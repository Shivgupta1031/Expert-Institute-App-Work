@extends('adminPanel/base')

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Settings</h6>
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
                <form method="post" action="{{ route('saveSettings') }}">
                    @csrf
                    @foreach ($data as $key => $value)
                        @if ($key !== 'id')
                            @if ($key == 'payment_method')
                                <div class="form-group">
                                    <label for="{{ $key }}"><span
                                            class="h4">{{ str_replace('_', ' ', strtoupper($key)) }}</span></label>
                                    <select name="{{ $key }}" class="custom-select">
                                        @if ($value == 0)
                                            <option selected value="0">Razorpay</option>
                                        @else
                                            <option value="0">Razorpay</option>
                                        @endif
                                        @if ($value == 1)
                                            <option selected value="1">Phone Pe</option>
                                        @else
                                            <option value="1">Phone Pe</option>
                                        @endif
                                        @if ($value == 2)
                                            <option selected value="2">Manual</option>
                                        @else
                                            <option value="2">Manual</option>
                                        @endif
                                    </select>
                                </div>
                            @else
                                <label for="{{ $key }}"><span
                                        class="h4">{{ str_replace('_', ' ', strtoupper($key)) }}</span></label>
                                <div class="input-group mb-3">
                                    <input type="text" name="{{ $key }}" class="form-control"
                                        value="{{ $value }}">
                                </div>
                            @endif
                        @endif
                    @endforeach
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>

        @include('adminPanel.footer')

    </div>

@endsection
