<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <style>
        /* Add your additional CSS styles and animations here */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .welcome-container {
            text-align: center;
            padding: 50px;
            background-color: #ffffff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            animation: fadeInUp 1s ease-out;
        }

        .app-icon {
            width: 100px;
            /* adjust the size as needed */
            height: 100px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        h1 {
            color: #007bff;
            margin-bottom: 10px;
        }

        p {
            color: #6c757d;
            margin-bottom: 20px;
        }

        .cta-button {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .cta-button:hover {
            background-color: #0056b3;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="welcome-container">
        <img src="{{ asset('images/logo.png') }}" alt="App Icon" class="app-icon">
        <h1>{{ env('APP_NAME') }}</h1>
        <p>Welcome to our awesome app! Explore the features and enjoy a seamless experience.</p>
        <a target="blank" href="https://play.google.com/store/search?c=apps&q={{ env('APP_NAME') }}"> <button
                class="cta-button">Download App</button></a>
        <br>
        <br>
        <a href="{{ route('privacypolicy') }}" class="btn btn-primary mr-2">Privacy Policy</a>
        ||
        <a href="{{ route('termsconditions') }}" class="btn btn-primary mr-2">Terms & Conditions</a>

    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
