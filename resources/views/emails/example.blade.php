<h3>Hi {{ $name }},</h3>
<h4>Password reset request</h4>

@php
    $token = Str::random(60);
@endphp

<h4>If you didn´t request to change your password, dont click on the link.</h4>
<p>Click <a href="{{ route('password.reset', ['token' => $token]) }}">here</a> to reset your password.</p>

<h5>-------</h5>
<h5>Y social network Staff</h5>
