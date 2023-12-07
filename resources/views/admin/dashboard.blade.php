@extends('layouts.app')

@section('content')

@auth('admin')
<h2> Admin dashboard </h2>

<ul>
    <li> Posts made today :  </li>
    <li> Posts made this week : {{$weekly_posts}} </li>
    <li> Posts made this month : {{$monthly_posts}} </li>
    <li> Posts made this year : {{$yearly_posts}} </li>
    <li> Total Posts : {{$total_posts}} </li>
</ul>
@endauth


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
      <tr class="table-active">
        <th scope="row">Posts</th>
        <td>{{$daily_posts}}</td>
        <td>{{$weekly_posts}}</td>
        <td>{{$monthly_posts}}</td>
        <td>{{$yearly_posts}}</td>
        <td>{{$total_posts}}</td>
      </tr>
      <tr>
        <th scope="row">Private Messages</th>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
      </tr>
      <tr>
        <th scope="row">Group Messages</th>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
        <td>Column content</td>
      </tr>
      <tr>
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