<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body { 
            background: #f8f8fb url('/login-register-image.jpg') no-repeat center center fixed; 
            background-size: cover; 
            font-family: Arial, sans-serif; 
            margin: 0; 
        }
        .container { max-width: 400px; margin: 4rem auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); padding: 2.5rem 2rem; }
        .site-title { text-align: center; color: #23234c; font-size: 1.1rem; font-weight: 700; margin-bottom: 2.2rem; line-height: 1.4; }
        h1 { text-align: center; color: #3b3b6d; font-size: 2rem; margin-bottom: 1.5rem; }
        label { color: #23234c; font-weight: 600; margin-top: 1rem; display: block; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 0.7rem; border-radius: 6px; border: 1px solid #ccc; font-size: 1rem; margin-top: 0.5rem; margin-bottom: 1.2rem; }
        button[type="submit"] { width: 100%; background: #3b3b6d; color: #fff; border: none; padding: 0.7rem; border-radius: 6px; font-size: 1.1rem; font-weight: 600; cursor: pointer; margin-top: 1rem; }
        button[type="submit"]:hover { background: #5a5ad1; }
        .login-link { text-align: center; margin-top: 1.5rem; color: #666; }
        .login-link a { color: #5a5ad1; text-decoration: none; font-weight: 600; }
        .login-link a:hover { text-decoration: underline; }
        .success-msg { color: green; text-align: center; margin-bottom: 1rem; }
        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 1.2rem;
        }
        .nasom-logo {
            height: 54px;
            width: auto;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-wrapper">
            <img src="/nasom-logo.png" alt="NASOM Logo" class="nasom-logo">
        </div>
        <div class="site-title">
            Peer Support Web-based System for Caregivers of Children with Autism<br>
            (PeerCare Forum)<br>
            by<br>
            National Autism Society of Malaysia (NASOM)
        </div>
        <h1>Register</h1>
        @if(session('success'))
            <div class="success-msg">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div style="color: #e74c3c; margin-bottom: 1rem; font-weight: bold; text-align:center;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <form method="POST" action="/register">
            @csrf
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
            <button type="submit">Register</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </div>
    </div>
</body>
</html>
