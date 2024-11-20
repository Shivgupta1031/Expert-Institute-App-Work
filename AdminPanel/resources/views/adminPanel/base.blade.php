<!DOCTYPE html>
<html>

@include('adminPanel/head')
@section('css')
    @yield('css')
@endsection

<body>

    @include('adminPanel/sidemenu')

    <!-- Main content -->
    <div class="main-content" id="panel">

        <!-- Topnav -->
        <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
            <div class="container-fluid">
                <span id="myNavbarTitle" class="text-lighter font-weight-bolder">{{ env('APP_NAME') }}</span>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav align-items-center ml-md-auto ">
                        <li class="nav-item d-xl-none">
                            <!-- Sidenav toggler -->
                            <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin"
                                data-target="#sidenav-main">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')

    </div>

    @include('adminPanel/js')

    <script>
        $(document).ready(function() {

            $('#image').change(function() {
                var file = $('#image')[0].files[0].name;
                document.getElementById("imageName").innerHTML = file;
                document.getElementById("imageUrl").value = "";
            });

            $('#imageUrl').change(function() {
                if ($(this).value != "") {
                    document.getElementById("imageName").innerHTML = "Choose File";
                    document.getElementById("image").value = "";
                }
            });

            $('#image2').change(function() {
                var file = $('#image2')[0].files[0].name;
                document.getElementById("imageName2").innerHTML = file;
                document.getElementById("imageUrl2").value = "";
            });

            $('#imageUrl2').change(function() {
                if ($(this).value != "") {
                    document.getElementById("imageName2").innerHTML = "Choose File";
                    document.getElementById("image2").value = "";
                }
            });
        });
    </script>
    @section('js')
        @yield('js')
    @endsection

</body>

</html>
