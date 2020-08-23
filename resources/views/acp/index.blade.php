@extends('layouts.main')

@section('content')

    <h1 class="page-header">{{ __('site.admin_control_panel') }}</h1>
    <hr />

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        <div>
            <h2 class="page-subheader">{{ __('user.users') }}</h2>
            <div class="page-section">
                <ul>
                    @foreach ($users as $user)
                        <li class="list-item"> <a href="{{ route('profile', $user->id) }}" class="link"> <i class="fad fa-user px-2 text-cgwhite"></i> {{ $user->name }}</a>
                    @endforeach
                </ul>
            </div>

            <div class="page-section">
                <i class="fad fa-users"></i> <span class="highlight"> {{ __('user.count', ['count' => $userCount]) }}</span><br />[ <a href="{{ route('user-list') }}" class="link">{{ __('user.view_all') }}</a> ]
            </div>
        </div>


        <div>
            <h2 class="page-subheader">{{ __('site.global_roles') }}</h2>
            <div class="page-section">
                <ul>
                    @foreach ($roles as $role)
                        <li class="list-item"> <span class="role-tag bg-{{ $role->color }}"><a href="{{ route('role-list') }}">{{ $role->name }}</a></span>
                    @endforeach
                </ul>
            </div>

            <div class="page-section">
                [ <a href="{{ route('role-list') }}" class="link">{{ __('site.view_global_roles') }}</a> ]
            </div>
        </div>


        <div>
            <h2 class="page-subheader">{{ __('game.stats') }}</h2>
            <div class="page-section">
                <ul>
                    <li> <a href="{{ route('game-list') }}" class="link"><i class="fad fa-game-console-handheld text-xl text-white"></i> {{ __('game.count', ['count' => $gameCount]) }}</a></li>
                </ul>
            </div>

            <div class="page-section">
                <ul>
                    <li> <a href="{{ route('genre-list') }}" class="link"><i class="fad fa-alien-monster text-xl text-cgpink-500"></i></i> {{ __('game.genre_count', ['count' => $genreCount]) }}</a></li>
                </ul>
            </div>

        </div>

    </div>
@endsection