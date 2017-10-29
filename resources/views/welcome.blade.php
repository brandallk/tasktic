<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Welcome to Tasktic!</title>
        
    </head>
    <body>
            <nav>
                <ul>
                    <li><a href="{{ route('about') }}">About Tasktic</a></li>
                </ul>
            </nav>

            <hr>

            <div class="welcome">
                <h1>Welcome to Tasktic!</h1>
                <span>Get Organized. Create your daily todo-list online!</span>

                @if (Route::has('login'))
                    <div class="authLinks">
                        @auth
                            <button type="button" name="home">
                                <a href="{{ url('/home') }}">Home</a>
                            </button>
                        @else
                            <button type="button" name="login">
                                <a href="{{ route('login') }}">Login</a>
                            </button>
                            <button type="button" name="register">
                                <a href="{{ route('register') }}">Register</a>
                            </button>
                        @endauth
                    </div>
                @endif
            </div>
    </body>
</html>
