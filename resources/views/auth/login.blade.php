<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Facebook – log in or sign up</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: white;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
            width: 360px;
        }

        .login-box h2 {
            color: #1877f2;
            text-align: center;
            font-size: 28px;
            margin-bottom: 24px;
        }

        .login-box label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .login-box input {
            width: 100%;
            padding: 10px;
            margin-bottom: 14px;
            border: 1px solid #dddfe2;
            border-radius: 6px;
            font-size: 16px;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background-color: #1877f2;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .login-box button:hover {
            background-color: #166fe5;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 16px;
        }

        .extra {
            text-align: center;
            margin-top: 12px;
        }

        .extra a {
            color: #1877f2;
            text-decoration: none;
            font-size: 14px;
        }

        .extra a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h2>Facebook</h2>

           {{-- Success --}}
@if (session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif

{{-- Errors --}}
@if ($errors->any())
    <div style="color: red;">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif


            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">Log In</button>
            </form>

            <div class="extra">
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            </div>
        </div>
    </div>
</body>
</html>
