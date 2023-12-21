@extends('layouts.app')

@section('content')

<div class="container">
    <h2>Admin Dashboard</h2>

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
                    <td>{{$daily_messages}}</td>
                    <td>{{$weekly_messages}}</td>
                    <td>{{$monthly_messages}}</td>
                    <td>{{$yearly_messages}}</td>
                    <td>{{$total_messages}}</td>
                </tr>
                <tr class="table-dark">
                    <th scope="row">Group Messages</th>
                    <td>{{$daily_group_messages}}</td>
                    <td>{{$weekly_group_messages}}</td>
                    <td>{{$monthly_group_messages}}</td>
                    <td>{{$yearly_group_messages}}</td>
                    <td>{{$total_group_messages}}</td>
                </tr>
                <tr class="table-dark">
                    <th scope="row">Comments</th>
                    <td>{{$daily_comments}}</td>
                    <td>{{$weekly_comments}}</td>
                    <td>{{$monthly_comments}}</td>
                    <td>{{$yearly_comments}}</td>
                    <td>{{$total_comments}}</td>
                </tr>
            </tbody>
        </table>

        <div class="row mt-4">
          <div class="col-md-6">
              <div class="card mb-3">
                  <div class="card-body">
                      <h5 class="card-title">Moderators</h5>
                      <p class="card-text" style="font-size: 2em;">{{$total_moderators}}</p>
                      <a href="/admin/moderators" class="btn btn-primary">List of Moderators</a>
                  </div>
              </div>
          </div>
          <div class="col-md-6">
              <div class="card mb-3">
                  <div class="card-body">
                      <h5 class="card-title">Users</h5>
                      <p class="card-text" style="font-size: 2em;">{{$total_users}}</p>
                      <a href="/admin/users" class="btn btn-success">List of Users</a>
                  </div>
              </div>
          </div>
      </div>

      <div class="row mt-4">
          <div class="col-md-6">
              <div class="card mb-3">
                  <div class="card-body">
                      <h5 class="card-title">Posts</h5>
                      <p class="card-text" style="font-size: 2em;">{{$total_posts}}</p>
                      <a href="/admin/posts" class="btn btn-danger">List of Posts</a>
                  </div>
              </div>
          </div>

          <div class="col-md-6">
              <div class="card mb-3">
                  <div class="card-body">
                      <h5 class="card-title">Groups</h5>
                      <p class="card-text" style="font-size: 2em;">{{$total_groups}}</p>
                      <a href="/admin/groups" class="btn btn-warning">List of Groups</a>
                  </div>
              </div>
          </div>
      </div>
    @endauth
</div>

@endsection
