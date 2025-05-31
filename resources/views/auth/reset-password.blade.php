@extends('layouts.app')

@section('content')
<h2>Reset Password</h2>

<p>Token: {{ request()->get('token') }}</p>
<p>Email: {{ request()->get('email') }}</p>

<form method="POST" action="{{ url('/reset-password') }}">
    @csrf
    <input type="hidden" name="token" value="{{ request()->get('token') }}">
    <input type="hidden" name="email" value="{{ request()->get('email') }}">

    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="New Password" required>
    </div>
    <div class="form-group">
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password" required>
    </div>
    <button type="submit" class="btn">Reset Password</button>
</form>

<a href="/login" class="link">Back to Login</a>
@endsection
