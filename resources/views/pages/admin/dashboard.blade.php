
@extends('layouts.app')

@section('content')

<h2> Admin dashboard </h2>



@auth('admin')
<table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">Action</th>
        <th scope="col">Today</th>
        <th scope="col">Last 7 Days</th>
        <th scope="col">Last Month</th>
        <th scope="col">Last Year</th>
        <th scope="col">Total</th>
      </tr>
    </thead>
    <tbody>
      <tr class="table-dark">
        <th scope="row">Posts</th>
        <td>{{$daily_posts}}</td>
        <td>{{$weekly_posts}}</td>
        <td>{{$monthly_posts}}</td>
        <td>{{$yearly_posts}}</td>
        <td>{{$total_posts}}</td>
      </tr>
      <tr class="table-dark">
        <th scope="row">Private Messages</th>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
      </tr>
      <tr class="table-dark">
        <th scope="row">Group Messages</th>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
      </tr>
      <tr class="table-dark">
        <th scope="row">New Accounts</th>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
      </tr>
    </tbody>
  </table>
@endauth

@endsection
