<!DOCTYPE html>
<html>

@include('adminPanel/head')

<body class="bg-default">

    <!-- Main content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
                    xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>
        <!-- Page content -->
        <div class="container mt--8 pb-3">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card bg-secondary border-0 mb-0">
                        <div class="card-body px-lg-5 py-lg-5">
                            @if ($is_error)
                                <div id="error-alert" class="alert alert-danger alert-dismissible fade show"
                                    data-auto-dismiss="3000" role="alert">
                                    <span class="fa fa-check"></span> {{ $error }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <div class="card-img text-center text-muted mb-0">
                                <img class="img-fluid rounded" src="{{ asset('images/logo.png') }}" width="180"
                                    height="180">
                            </div>
                            <br>
                            <form method="post" action="/adminpanel/panel/loginUser" role="form">
                                @csrf
                                <div class="form-group mb-0">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Email" name="email" type="email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Password" name="password"
                                            type="password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="adminTypeSelect"><span class="h4">Admin Type</span></label>
                                    <select name="adminTypeSelect" id="adminTypeSelect" class="custom-select">
                                        <option selected value="0">Main Admin</option>
                                        <option value="1">Teacher</option>
                                    </select>
                                </div>
                                <!-- <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id=" customCheckLogin" type="checkbox">
                  <label class="custom-control-label" for=" customCheckLogin">
                    <span class="text-muted">Remember me</span>
                  </label>
                </div> -->
                                <div class="text-center">
                                    <button type="submit" name="submit" class="btn btn-primary my-2">Sign in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- <div class="row mt-3">
            <div class="col-6">
              <a href="#" class="text-light"><small>Forgot password?</small></a>
            </div>
            <div class="col-6 text-right">
              <a href="#" class="text-light"><small>Create new account</small></a>
            </div>
          </div> -->
                </div>
            </div>
        </div>
    </div>

    @include('adminPanel/js')

</body>

</html>
