@extends('layouts.app')

@section('content')
<h2>Forgot Password</h2>

<form method="POST" action="/forgot-password">
    @csrf
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
    </div>
    <button type="submit" class="btn">Send Reset Link</button>
</form>

<a href="/login" class="link">Back to Login</a>
@endsection
