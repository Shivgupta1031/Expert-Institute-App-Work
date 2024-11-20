<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ env('APP_NAME') }} - Admin Panel</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('adminPanel/vendor/nucleo/css/nucleo.css') }}" type="text/css">
    {{-- <link rel="stylesheet" href="{{ asset('adminPanel/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}"
        type="text/css"> --}}
    <!-- Page plugins -->
    <!-- Argon CSS -->
    <link rel="stylesheet" href="{{ asset('adminPanel/css/argon.css') }}" type="text/css">
    <!--load all styles -->
    <link rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @yield('css')

</head>
