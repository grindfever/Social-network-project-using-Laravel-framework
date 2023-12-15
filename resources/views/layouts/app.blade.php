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
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
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
                    <input class="form-control me-sm-2" name="query" type="search" placeholder="search">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                  </form>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/messages') }}">Messages</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/groups') }}">Groups</a>
                  </li>

                  
                

                @if (Auth::check())
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/profile') }}" >{{ Auth::user()->name }}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/about') }}" >About us</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/contact') }}" >Contact us</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/logout') }}">Logout</a> 
                  </li>
                  @else
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/login') }}" >Login</a>
                  </li>
                  @endif
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
            <button id="scrollToTopButton" onclick="scrollToTop()">Scroll to top</button>
          </section>
        </main>
    </body>
</html>

