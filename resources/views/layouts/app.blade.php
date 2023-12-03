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
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    <body>

    <main>
        <header1>

            <div class="logo">
                <a href="{{ url('/dashboard') }}">Thingy!</a>
            </div>
            <div class="timeline">
                <a href="{{ url('/dashboard') }}">Timeline</a>
            </div>
            <div class="messages">
                <a href="{{ url('/messages') }}">Messages</a>
            </div>
            
            @if (Auth::check())
            <div class="user-info">
             <span><a href="{{ url('/profile') }}" >{{ Auth::user()->name }}</a></span>
            </div>
            <div class="logout">
            <a class="button" href="{{ url('/logout') }}">Logout</a>
            </div>    
            @endif
        </header1>
   
        <header2> 
            <a class="button" href="{{ url('/search') }}"> Search </a>
        <div class="Profile">
                <a href="{{ url('/profile') }}">MyProfile</a>
                
            </div>  
        </header2>   
 
        <section id="content">
            @yield('content')
        </section>
    </main>

    </body>
</html>

