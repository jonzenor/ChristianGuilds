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
                <a href="{{ route('home') }}"><img src="{{ asset('images/ChristianGuilds-2-Color.png') }}"></a>
            </div>
        </div>

        <div class="w-full flex">
            <div class="w-auto flex-grow">
                <ul class="flex pl-3 pt-4 items-center">
                    <li class="mr-6">
                        <a class="nav-links" href="{{ route('home') }}">Home</a>
                    </li>

                    @can('view-acp')
                        <li class="mr-6">
                            <a class="nav-links" href="{{ route('acp') }}">ACP</a>
                        </li>
                    @endcan

                    @can('manage-games')
                        @if (getPendingGamesCount())
                            <li class="mr-6">
                                <a href="{{ route('game-list-pending') }}" class="nav-links"><i class="fas fa-exclamation-triangle text-xl text-red-300"></i> {{ getPendingGamesCount() }}</a>
                            </li>
                        @endif
                    @endcan

                    @if (env("APP_ENV") == "forge" || env("APP_ENV") == "local")
                        <li class="mr-6">
                            <a class="nav-links" href="{{ route('forge-power-up') }}">Forge Power Up</a>
                        </li>
                    @endif

                    @auth
                        @if (auth()->user()->guilds()->count() || auth()->user()->communities()->count())
                            <div class="dropdown inline-block relative">
                                <button class="text-cgwhite py-2 px-4 rounded inline-flex items-center button-blue">
                                <span class="mr-1">{{ __('guild.my_guilds') }}</span>
                                <i class="fad fa-chevron-down"></i>
                                {{-- <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/> </svg> --}}
                                </button>

                                <ul class="dropdown-menu absolute hidden text-gray-700 pt-1">
                                    @if (auth()->user()->communities()->count())
                                        <?php $count = 0; ?>
                                        @foreach (auth()->user()->communities as $userCommunity)
                                            <?php
                                                $rounded = "";
                                                $count ++;
                                                if ($count == 1) { $rounded .= "rounded-t"; }
                                                if ($count == auth()->user()->communities()->count()) { $rounded .= " rounded-b"; }
                                            ?>
                                            
                                            <li><a class="bg-cgblue-100 hover:bg-cgblue-300 py-2 px-4 block whitespace-no-wrap {{ $rounded }}" href="{{ route('community', $userCommunity->id) }}">{{ $userCommunity->name }}</a></li>
                                        @endforeach
                                    @endif

                                    @if (auth()->user()->guilds()->count() && auth()->user()->communities()->count())
                                        <li class="leading-tight"> &nbsp;</li>
                                    @endif

                                    @if (auth()->user()->guilds()->count())
                                        <?php $count = 0; ?>
                                        @foreach (auth()->user()->guilds as $userGuild)
                                            <?php
                                                $rounded = "";
                                                $count ++;
                                                if ($count == 1) { $rounded .= "rounded-t"; }
                                                if ($count == auth()->user()->guilds()->count()) { $rounded .= " rounded-b"; }
                                            ?>
                                            
                                            <li><a class="bg-gray-200 hover:bg-gray-400 py-2 px-4 block whitespace-no-wrap {{ $rounded }}" href="{{ route('guild', $userGuild->id) }}">{{ $userGuild->name }}</a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        @endif
                    @endauth
                    
                </ul>
            </div>
            <div>
                <ul class="flex pl-3 pt-4 items-center justify-center">

                    <form action="{{ route('search') }}" method="post">
                        @csrf

                        <div class="pr-6">
                            <div class="bg-white flex items-center rounded-full shadow-xl">
                                <input name="search" class="rounded-l-full w-full text-cggray-700 leading-tight focus:outline-none pl-3" id="search" type="text" placeholder="Search">
                              
                                <div class="pl-3 pr-1 py-1">
                                    <button type="submit" class="bg-cgblue-500 text-cgwhite rounded-full hover:bg-cgblue-400 focus:outline-none flex items-center justify-center p-2">
                                        <i class="far fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    @if(isset($errors))
                        @error('search')
                            <p class="text-red-500 text-xs italic mt-4">
                                {{ $message }}
                            </p>
                        @enderror
                    @endif
                    
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
                </ul>
            </div>
        </div>
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