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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="{{ asset('js/newUserDisplay.js') }}"></script>
    <script src="{{ asset('js/cardsListHeightNormalization.js') }}"></script>
    <script src="{{ asset('js/alertDismiss.js') }}"></script>
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
                                        <a class="nav-link text-white"
                                            href="{{ route('login') }}">{{ __('Prisijungti') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#"
                                        role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        v-pre>
                                        @if (Auth::user()->person)
                                            {{ Auth::user()->person->name }} {{ Auth::user()->person->surname }}
                                        @else
                                            Teorijos egzaminavimas
                                        @endif
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('pw.form') }}">
                                            {{ __('Pasikeisti slaptažodį') }}
                                        </a>

                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{ __('Atsijungti') }}
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
                <div
                    class="bg-secondary col-auto col-md-3 col-lg-3 col-xl-2 min-vh-100 d-flex flex-column justify-content-between">
                    <div class="no-print">
                        <div class="bg-secondary p-2">
                            <ul class="nav nav-pills flex-column mt-4">
                                <li class="nav-item py-2 py-sm-0">
                                    <a href="{{ route('branch.list') }}"
                                        class="nav-link text-white {{ request()->is('branch*') == 1 ? 'active' : '' }}">
                                        <i class="fs-6 fa fa-building"></i> <span
                                            class="fs-6 ms-2 d-none d-sm-inline">Filialai</span>
                                    </a>
                                </li>

                                <li class="nav-item py-2 py-sm-0">
                                    <a href="{{ route('pricing.list') }}"
                                        class="nav-link text-white {{ request()->is('pricing*') == 1 ? 'active' : '' }}">
                                        <i class="fs-6 fa fa-euro-sign"></i> <span
                                            class="fs-6 ms-2 d-none d-sm-inline">Kainos</span>
                                    </a>
                                </li>

                                <li class="nav-item py-2 py-sm-0">
                                    <a href="{{ route('course.list') }}"
                                        class="nav-link text-white {{ request()->is('course*') == 1 ? 'active' : '' }}">
                                        <i class="fs-6 fa fa-car-side"></i> <span
                                            class="fs-6 ms-2 d-none d-sm-inline">Kursai</span>
                                    </a>
                                </li>

                                <li class="nav-item py-2 py-sm-0">
                                    <a href="{{ route('instructor.list') }}"
                                        class="nav-link text-white {{ request()->is('instructor*') == 1 ? 'active' : '' }}">
                                        <i class="fs-6 fa fa-chalkboard-user"></i> <span
                                            class="fs-6 ms-2 d-none d-sm-inline">Instruktoriai</span>
                                    </a>
                                </li>

                                <li class="nav-item py-2 py-sm-0">
                                    <a href="{{ route('video.list') }}"
                                        class="nav-link text-white {{ request()->is('video*') == 1 ? 'active' : '' }}">
                                        <i class="fs-6 fa fa-video"></i> <span
                                            class="fs-6 ms-2 d-none d-sm-inline">Vaizdo įrašai</span>
                                    </a>
                                </li>

                                <li class="nav-item py-2 py-sm-0">
                                    <a href="{{ route('link.list') }}"
                                        class="nav-link text-white {{ request()->is('information*') == 1 ? 'active' : '' }}">
                                        <i class="fs-6 fa fa-link"></i> <span
                                            class="fs-6 ms-2 d-none d-sm-inline">Naudinga informacija</span>
                                    </a>
                                </li>

                                @Auth
                                    @if (Auth::user()->role == 'client')
                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('contract.client') }}"
                                                class="nav-link text-white {{ request()->is('contracts*') == 1 ? 'active' : '' }}">
                                                <i class="fs-6 fa fa-file-contract"></i> <span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Sutartys</span>
                                            </a>
                                        </li>

                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('lesson') }}"
                                                class="nav-link text-white {{ request()->is('lesson*') == 1 ? 'active' : '' }}">
                                                <i class="fs-6 fa fa-road"></i> <span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Vairavimų pamokos</span>
                                            </a>
                                        </li>

                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('documents') }}"
                                                class="nav-link text-white {{ request()->is('documents*') == 1 ? 'active' : '' }}">
                                                <i class="fs-6 fa fa-file-import"></i> <span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Dokumentai</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::user()->role == 'director')
                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('employee.list') }}"
                                                class="nav-link text-white {{ request()->is('employee*') == 1 ? 'active' : '' }}">
                                                <i class="fa fa-user-tie"></i> <span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Darbuotojai</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if (Auth::user()->role == 'director' || Auth::user()->role == 'instructor')
                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('slides.list') }}"
                                                class="nav-link text-white {{ request()->is('slide*') == 1 ? 'active' : '' }}">
                                                <i class="fa-regular fa-file-powerpoint"></i><span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Teorijos skaidrės</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if (Auth::user()->role == 'instructor')
                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('lesson.upcoming') }}"
                                                class="nav-link text-white {{ request()->is('lessons/upcoming') == 1 ? 'active' : '' }}">
                                                <i class="fa-regular fa-calendar"></i> <span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Vairavimų tvarkaraštis</span>
                                            </a>
                                        </li>

                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('lesson.grades') }}"
                                                class="nav-link text-white {{ request()->is('lessons/grades') == 1 ? 'active' : '' }}">
                                                <i class="fa-solid fa-pencil"></i><span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Įvykusių vairavimų
                                                    įvertinimas</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if (Auth::user()->role == 'director' || Auth::user()->role == 'administrator')
                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('client.list') }}"
                                                class="nav-link text-white {{ request()->is('client*') == 1 ? 'active' : '' }}">
                                                <i class="fa-solid fa-graduation-cap"></i><span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Mokiniai</span>
                                            </a>
                                        </li>

                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('contract.list') }}"
                                                class="nav-link text-white {{ request()->is('contract*') == 1 ? 'active' : '' }}">
                                                <i class="fa-solid fa-file-contract"></i><span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Sutarčių užklausos</span>
                                            </a>
                                        </li>

                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('contract.requestless') }}"
                                                class="nav-link text-white {{ request()->is('add/contract') == 1 ? 'active' : '' }}">
                                                <i class="fa-solid fa-file-arrow-up"></i><span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Įkelti sutartį be užklausos</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if (Auth::user()->role == 'director' || Auth::user()->role == 'accountant')
                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('accounting.salary') }}"
                                                class="nav-link text-white {{ request()->is('accounting/salaries') == 1 ? 'active' : '' }}">
                                                <i class="fa-solid fa-wallet"></i><span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Suvesti atlyginimų išlaidas</span>
                                            </a>
                                        </li>

                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('accounting.income') }}"
                                                class="nav-link text-white {{ request()->is('accounting/income') == 1 ? 'active' : '' }}">
                                                <i class="fa-solid fa-cash-register"></i><span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Suvesti pajamas</span>
                                            </a>
                                        </li>

                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('accounting.expense') }}"
                                                class="nav-link text-white {{ request()->is('accounting/expense') == 1 ? 'active' : '' }}">
                                                <i class="fa-solid fa-receipt"></i><span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Suvesti išlaidas</span>
                                            </a>
                                        </li>

                                        <li class="nav-item py-2 py-sm-0">
                                            <a href="{{ route('accounting.report') }}"
                                                class="nav-link text-white {{ request()->routeIs('accounting.report') ? 'active' : '' }}">
                                                <i class="fa-solid fa-chart-simple"></i><span
                                                    class="fs-6 ms-2 d-none d-sm-inline">Mėnesinė finansinė ataskaita</span>
                                            </a>
                                        </li>
                                    @endif
                                @endauth
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
