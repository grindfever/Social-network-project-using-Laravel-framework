@extends('layouts.app')

@section('content')

@auth('admin')
<h2> Admin </h2>
@endauth

@auth('web')
<h2> User </h2>
@endauth

@guest ('admin')
<h2> guest </h2>
@endguest



@endsection