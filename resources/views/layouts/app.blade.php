<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
</head>
<body>
    <div id="app">
            <!-- Sidebar -->
            <button id="buttonNavbar" class="btn btn-block btn-dark">Menu</button>
            <nav id="sidebar">
                <div class="sidebar-header">
                    <h3>PLO Trainer</h3>
                </div>
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <p>{{ Auth::user()->name }} <span class="caret"></span></p>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                </ul>
                <ul class="list-unstyled components">
                    <p><b>Training</b></p>
                    <li>
                        <a href="{{ route('randomSituation') }}">Random Situations</a>
                    </li>
                    <li>
                        <a href="{{ route('selectSituation') }}">Choose Situations</a>
                    </li>
                    @if(Auth::user()->admin == 1)
                    <li>
                        <a href="{{ route('randomSituationAdmin') }}">Random Situation Admin</a>
                    </li>
                    <p><b>Settings</b></p>
                    <li>
                        <a href="{{ route('createSituation') }}">Create new Situation</a>
                    </li>
                    <li>
                        <a href="{{ route('editSituation') }}">Edit Situation</a>
                    </li>
                    @endif
                    <p><b>Statistics</b></p>
                    <li>
                        <a href="{{ route('showStatistics') }}">View my statistics</a>
                    </li>
                    @if(Auth::user()->admin == 1)
                    <li>
                        <a href="{{ route('showAllStatistics') }}">View all statistics</a>
                    </li>
                    <p><b>UserManagement</b></p>
                    <li>
                        <a href="{{ route('showCreateUser') }}">Create User</a>
                    </li>
                    <li>
                        <a href="{{ route('showUsers') }}">Show Users</a>
                    </li>
                    @endif
                    @endguest
                </ul>
            </nav>
        <div id="content">
            @yield('content')
        </div>
    </div>
    <script src="http://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous">
    </script>
    <script>
        jQuery(document).ready(function() {
            jQuery('#buttonNavbar').click(function (e) {
                e.preventDefault();
                if(document.getElementById('sidebar').style.display == "block") {
                    document.getElementById('sidebar').style.cssText = "display:none !important";
                    document.getElementById('content').style.cssText = "display:block !important";
                }else{
                    document.getElementById('sidebar').style.cssText = "display:block !important";
                    document.getElementById('content').style.cssText = "display:none !important";
                }
            });
        })
    </script>
    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

</body>
</html>
