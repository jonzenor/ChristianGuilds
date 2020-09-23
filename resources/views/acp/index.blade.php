@extends('layouts.main')

@section('content')

    <h1 class="page-header">{{ __('site.admin_control_panel') }}</h1>
    <hr />

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        @can('manage-users')
            <div>
                <h2 class="section-header">{{ __('user.users') }}</h2>
                <div class="page-section">
                    <ul>
                        @foreach ($users as $user)
                            <li class="list-item"> <a href="{{ route('profile', $user->id) }}" class="link"> <i class="fad fa-user px-2 text-cgwhite"></i> {{ \Illuminate\Support\Str::limit($user->name, config('site.truncate_length'), $end='...') }}</a>
                        @endforeach
                    </ul>
                </div>

                <div class="page-section">
                    <i class="fad fa-users"></i> <span class="highlight"> {{ __('user.count', ['count' => $count['users']]) }}</span><br />[ <a href="{{ route('user-list') }}" class="link">{{ __('user.view_all') }}</a> ]
                </div>
            </div>
        @endcan

        @can('manage-guilds')
            <div>
                <h2 class="section-header">{{ __('guild.guilds') }}</h2>
                <div class="page-section">
                    <ul>
                        @foreach ($guilds as $guild)
                            <li class="list-item"> <a href="{{ route('guild', $guild->id) }}" class="link"> <i class="fad fa-pennant px-2 text-cgwhite text-lg"></i> {{ \Illuminate\Support\Str::limit($guild->name, config('site.truncate_length'), $end='...') }}</a>
                        @endforeach
                    </ul>
                </div>

                <div class="page-section">
                    <i class="fad fa-users-crown text-xl"></i> <span class="highlight"> {{ __('guild.count', ['count' => $count['guilds']]) }}</span><br />[ <a href="{{ route('guild-list') }}" class="link">{{ __('guild.view_all') }}</a> ]
                </div>

            </div>
        @endcan

        @can('manage-guilds')
            <div>
                <h2 class="section-header">{{ __('community.communities') }}</h2>
                <div class="page-section">
                    <ul>
                        @foreach ($communities as $community)
                            <li class="list-item"> <a href="{{ route('community', $community->id) }}" class="link"> <i class="fad fa-pennant px-2 text-cgwhite text-lg"></i> {{ \Illuminate\Support\Str::limit($community->name, config('site.truncate_length'), $end='...') }}</a>
                        @endforeach
                    </ul>
                </div>

                <div class="page-section">
                    <i class="fad fa-users-crown text-xl"></i> <span class="highlight"> {{ __('community.count', ['count' => $count['communities']]) }}</span><br />[ <a href="{{ route('community-list') }}" class="link">{{ __('community.view_all') }}</a> ]
                </div>

            </div>
        @endcan

        @can('manage-roles')
            <div>
                <h2 class="section-header">{{ __('site.global_roles') }}</h2>
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
        @endcan

        @can('manage-games')
            <div>
                <h2 class="section-header">{{ __('game.stats') }}</h2>
                <div class="page-section">
                    <ul>
                        <li> <a href="{{ route('game-list') }}" class="link"><i class="fad fa-game-console-handheld px-2 text-xl text-white"></i> {{ __('game.count', ['count' => $count['games']]) }}</a></li>
                        @if ($count['games_pending'])<li> <a href="{{ route('game-list-pending') }}" class="link"><i class="fas fa-exclamation-triangle text-xl text-red-300"></i> {{ __('game.pending_count', ['count' => $count['games_pending']]) }}</a></li>@endif
                    </ul>
                </div>

                <div class="page-section">
                    <ul>
                        <li> <a href="{{ route('genre-list') }}" class="link"><i class="fad fa-alien-monster text-xl text-cgpink-500"></i></i> {{ __('game.genre_count', ['count' => $count['genres']]) }}</a></li>
                    </ul>
                </div>

            </div>
        @endcan

    </div>
@endsection