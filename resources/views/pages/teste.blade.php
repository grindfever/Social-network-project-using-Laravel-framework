



@extends('layouts.app')

@section('content')

@auth('admin')
<h2> Admin </h2>
@endauth

@auth('web')
<h2> User </h2>
@endauth

@guest('admin')
@guest('web')
<h2> Guest </h2>
@endguest
@endguest

@endsection