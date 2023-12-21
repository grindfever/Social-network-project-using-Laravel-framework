@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}
    <div class="form-floating mb-3">
      <input type="name" name="name" class="form-control" id="floatingInput" placeholder="name" required>
      <label for="floatingInput">Name*</label>
    </div>
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }}
      </span>
    @endif

    <div class="form-floating mb-3">
      <input type="text" name="username" class="form-control" id="floatingInput" placeholder="username" required>
      <label for="floatingInput">Username</label>
    </div>
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif
  

    <div class="form-floating mb-3">
      <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="{{ old('email') }}" required>
      <label for="floatingInput">Email address*</label>
    </div>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <div class="form-floating">
      <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" autocomplete="off" required>
      <label for="floatingPassword">Password*</label>
    </div>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif
    <div class="form-floating">
      <input name="password_confirmation" type="password" class="form-control" id="password-confirm" placeholder="Confirm password" autocomplete="off" required>
      <label for="password-confirm">Confirm password*</label>
    </div>
    <button class="btn btn-primary" type="submit">Register</button>
    <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
</form>
@endsection