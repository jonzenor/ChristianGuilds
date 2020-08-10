<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://kit.fontawesome.com/104658f4fb.js" crossorigin="anonymous"></script>
    
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="h-screen antialiased leading-none">
    <nav class="w-full">
        <div class="bg-gray-800 w-full">
            <div class="w-full mx-auto lg:w-1/2">
                <img src="{{ asset('images/ChristianGuilds.png') }}">
            </div>
        </div>
        <ul class="flex pl-3 pt-4">
            <li class="mr-6">
                <a class="text-blue-500 hover:text-blue-800" href="{{ route('home') }}">Home</a>
            </li>
            <li class="mr-6">
                <a class="text-blue-500 hover:text-blue-800" href="{{ route('acp') }}">ACP</a>
            </li>

            @guest
                <li class="mr-6">
                    <a class="text-blue-500 hover:text-blue-800" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>

                @if (Route::has('register'))
                    <li class="mr-6">
                        <a class="text-blue-500 hover:text-blue-800" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="mr-6">
                    <a href="{{ route('logout') }}" class="text-blue-500 hover:text-blue-800"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">{{ __('Logout') }}
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    {{ csrf_field() }}
                </form>

                <li class="mr-6">
                    <span class="text-blue-500 hover:text-blue-800">{{ Auth::user()->name }}</span>
                </li>
            @endguest
        </ul>
    </nav>

    @yield('content')

</body>
</html>