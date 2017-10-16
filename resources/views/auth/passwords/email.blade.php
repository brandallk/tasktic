@extends('layouts.app')

@section('content')
    <h1>Reset Password</h1>

    @if (session('status'))
        {{ session('status') }}
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}

        <label for="email">E-Mail Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        @if ($errors->has('email'))
            <span>{{ $errors->first('email') }}</span>
        @endif

        <button type="submit">Send Password Reset Link</button>
    </form>
@endsection
