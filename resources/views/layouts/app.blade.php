<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer></script>
        
    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
            <div class="container-fluid">
              <a class="navbar-brand" href="{{ url('/dashboard') }}">Y!</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav me-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="{{ url('/dashboard') }}">Home
                      <span class="visually-hidden">(current)</span>
                    </a>
                  </li>
                  <form class="d-flex" action="/dashboard">
                    @csrf
                    <label for="search-input">
                      <span class="visually-hidden">Search</span>
                      <input id="search-input" class="form-control me-sm-2" name="query" type="search" placeholder="Search">
                    </label>
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                  </form>
                  @auth('web')
                  @if(Auth::user()->isModerator())
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Moderation</a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{ url('/reports') }}" >Reports</a>
                      <a class="dropdown-item" href="{{ url('/admin/users') }}">Users</a> 
                      <a class="dropdown-item" href="{{ url('/admin/posts') }}">Posts</a> 
                      <a class="dropdown-item" href="{{ url('/admin/groups') }}">Groups</a>
                    </div>
                  </li>
                  @endif
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/messages') }}">Messages</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/groups') }}">Groups</a>
                  </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{ url('/profile/' . Auth::user()->id) }}" >{{ Auth::user()->name }}</a>
                    </li>
                  @endauth
                  @auth('admin')
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Administration</a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{ url('/admin') }}">Homepage</a> 
                      <a class="dropdown-item" href="{{ url('/reports') }}" >Reports</a>
                      <a class="dropdown-item" href="{{ url('/admin/users') }}">Users</a> 
                      <a class="dropdown-item" href="{{ url('/admin/posts') }}">Posts</a> 
                      <a class="dropdown-item" href="{{ url('/admin/groups') }}">Groups</a> 
                      <a class="dropdown-item" href="{{ url('/admin/moderators') }}">Moderators</a>
                    </div>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/logout') }}">Logout</a> 
                  </li>
                  @endauth
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">See also</a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{ url('/about') }}">About</a>
                      <a class="dropdown-item" href="{{ url('/features') }}">Features</a>
                      <a class="dropdown-item" href="{{ url('/contact') }}">Contact</a>
                    </div>
                  </li>
                  @auth('web')
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/logout') }}">Logout</a> 
                  </li>
                  @endauth
                  @guest('admin')
                  @guest('web')
                    <li class="nav-item">
                      <a class="nav-link" href="{{ url('/login') }}" >Login</a>
                    </li>
                  @endguest
                  @endguest
                </ul>
              </div>
            </div>
          </nav>      
          <section id="search-section">
            <div class="search-results-container">
                <div class="search-results"></div>
            </div>
          </section>  
        <main>
          <section id="content">
            @yield('content')
            <button class="btn btn-dark" id="scrollToTopButton" onclick="scrollToTop()">Scroll to top</button>
          </section>
        </main>
    </body>
</html>

