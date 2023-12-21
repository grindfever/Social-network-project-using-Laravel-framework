@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
        <label for="floatingInput">Email address</label>
    </div>
    @if ($errors->has('email'))
    <span class="error">
      {{ $errors->first('email') }}
    </span>
    @endif
    <div class="form-floating">
        <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" autocomplete="off" required>
        <label for="floatingPassword">Password</label>
    </div>
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
    </label>

    <button class="btn btn-primary "type="submit" style="margin-top:10px;">Login</button>
    <div class="login-buttons" style="margin-top: 10px;">
        <a class="btn btn-primary" href="/forgot-password-form">Forgot Your Password?</a>
        <a class="btn btn-primary" href="/register">Register</a>
    </div>
        
    @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif
</form>
@endsection