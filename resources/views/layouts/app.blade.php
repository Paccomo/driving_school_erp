<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Vairavimo mokyklų ERP') }}</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Icons -->
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v6.5.2/css/all.css">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="{{ asset('js/newUserDisplay.js') }}"></script>
</head>

<body>
    <div id="app">
        <div class="no-print">
            <nav class="navbar navbar-expand-md navbar-light bg-secondary shadow">
                <div class="container">
                    <a class="navbar-brand text-white" href="{{ url('/') }}">
                        {{ __('Vairavimo mokyklų ERP') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">

                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#"
                                        role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        v-pre>
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <div class="container-fluid">
            <div class="row flex-nowrap">
                <div class="bg-secondary col-auto col-md-3 col-lg-3 col-xl-2 min-vh-100 d-flex flex-column justify-content-between">
                    <div class="no-print">
                    <div class="bg-secondary p-2">
                        <ul class="nav nav-pills flex-column mt-4">
                            <li class="nav-item py-2 py-sm-0">
                                <a href="{{ route('register') }}"
                                    class="nav-link text-white {{ request()->is('register') == 1 ? 'active' : '' }}">
                                    <i class="fs-6 fa fa-gauge"></i> <span
                                        class="fs-6 ms-2 d-none d-sm-inline">Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item py-2 py-sm-0">
                                <a href="#" class="nav-link text-white">
                                    <i class="fs-6 fa fa-frog"></i><span
                                        class="fs-6 ms-2 d-none d-sm-inline">home</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    </div>
                </div>

                <main class="col-md-8 col-sm-7 col-lg-9 py-3">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
</body>

</html>
