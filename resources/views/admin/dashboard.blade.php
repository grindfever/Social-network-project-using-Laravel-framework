@extends('layouts.app')

@section('content')

@auth('admin')
<h2> Admin dashboard </h2>
@endauth

@endsection