@extends('layouts.app')

@section('content')
    <h2>Login</h2>
    <form method="POST" action="{{ url('/api/login') }}">
        @csrf
        <div class="form-group">
            <input type="email" name="email" placeholder="Email" class="form-control" required autofocus>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" class="form-control" required>
        </div>
        <button type="submit" class="btn">Log In</button>
    </form>
    <a href="#" class="link">Forgot password?</a>
@endsection
