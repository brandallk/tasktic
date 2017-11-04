<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Welcome to Tasktic!</title>

        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        
    </head>
    <body class="welcome-page">
            <nav>
                <ul>
                    <li><a href="{{ route('about') }}">About Tasktic</a></li>
                </ul>
            </nav>

            <div class="greeting">
                <h1>Welcome to Tasktic!</h1>
                <span>Get Organized. Create your daily todo-list online!</span>

                @if (Route::has('login'))
                    <div class="authLinks">
                        @auth
                            <a class="btn pink" href="{{ url('/home') }}">Home</a>
                        @else
                            <a class="btn pink" href="{{ route('login') }}">Log In</a>
                            <a class="btn pink" href="{{ route('register') }}">Register</a>
                        @endauth
                    </div>
                @endif
            </div>
    </body>
</html>
