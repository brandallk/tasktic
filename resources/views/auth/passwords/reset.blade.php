@extends('layouts.app')

@section('content')
    <h1>Reset Password</h1>

    <form method="POST" action="{{ route('password.request') }}">
        {{ csrf_field() }}

        <input type="hidden" name="token" value="{{ $token }}">

        <label for="email">E-Mail Address</label>
        <input id="email" type="email" name="email" value="{{ $email or old('email') }}" required autofocus>
        @if ($errors->has('email'))
            <span>{{ $errors->first('email') }}</span>
        @endif

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
        @if ($errors->has('password'))
            <span>{{ $errors->first('password') }}</span>
        @endif

        <label for="password-confirm">Confirm Password</label>
        <input id="password-confirm" type="password" name="password_confirmation" required>
        @if ($errors->has('password_confirmation'))
            <span>{{ $errors->first('password_confirmation') }}</span>
        @endif

        <button type="submit">Reset Password</button>                
    </form>
@endsection
