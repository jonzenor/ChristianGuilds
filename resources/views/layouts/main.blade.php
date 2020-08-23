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
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
    
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Tamma+2:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Markazi+Text&family=Noto+Sans+HK&display=swap" rel="stylesheet">

    <link rel="icon" href="{{ asset('images/favicon.png') }}">

    <style>
        .grecaptcha-badge { visibility: hidden; }
    </style>
</head>
<body class="h-screen antialiased leading-none bg-cgblack text-cgwhite font-body">
    <nav class="w-full">
        <div class="bg-cgblack-900 w-full">
            <div class="w-full mx-auto lg:w-1/2">
                <img src="{{ asset('images/ChristianGuilds-2-Color.png') }}">
            </div>
        </div>
        <ul class="flex pl-3 pt-4">
            <li class="mr-6">
                <a class="nav-links" href="{{ route('home') }}">Home</a>
            </li>
            @guest
                <li class="mr-6">
                    <a class="nav-links" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>

                @if (Route::has('register'))
                    <li class="mr-6">
                        <a class="nav-links" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="mr-6">
                    <a href="{{ route('logout') }}" class="nav-links"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">{{ __('Logout') }}
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    {{ csrf_field() }}
                </form>

                <li class="mr-6">
                    <a class="nav-links" href="{{ route('profile', Auth::user()->id) }}">{{ Auth::user()->name }}</a>
                </li>
            @endguest

            @can('view-acp')
                <li class="mr-6">
                    <a class="nav-links" href="{{ route('acp') }}">ACP</a>
                </li>
            @endcan

            @if (env("APP_ENV") == "forge" || env("APP_ENV") == "local")
                <li class="mr-6">
                    <a class="nav-links" href="{{ route('forge-power-up') }}">Forge Power Up</a>
                </li>
            @endif
        </ul>
    </nav>
    @include('sweetalert::alert')

    @if (config('site.message')) 
        <div class="mx-auto w-full sm:w-2/3 md:w-2/3 lg:w-1/2 text-center @if (config('site.message_type') == 'info') bg-cggreen-900 @else bg-cgpink-900 @endif rounded-lg border-cggray-500 border-2 p-4 my-3">
            <p>{!! config('site.message') !!}</p>
        </div>
    @endif

    @yield('content')

    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'contact'}).then(function(token) {
               if (token) {
                 document.getElementById('recaptcha').value = token;
               }
            });
        });
</script>

</body>
</html>