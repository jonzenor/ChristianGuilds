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
                        <li class="list-item"> <a href="{{ route('profile', $user->id) }}" class="link"> <i class="fal fa-user px-2"></i> {{ $user->name }}</a>
                    @endforeach
                </ul>
            </div>

            <div class="page-section">
                <i class="fal fa-users"></i> <span class="highlight"> {{ __('user.count', ['count' => $userCount]) }}</span><br />[ <a href="{{ route('user-list') }}" class="link">{{ __('user.view_all') }}</a> ]
            </div>
        </div>


        <div>
            <h2 class="page-subheader">{{ __('site.global_roles') }}</h2>
            <div class="page-section">
                <ul>
                    @foreach ($roles as $role)
                        <li class="list-item"> <span class="role-tag bg-{{ $role->color }}"><a href="#">{{ $role->name }}</a></span>
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
                    <li> <a href="{{ route('game-list') }}" class="link">{{ __('game.count', ['count' => $gameCount]) }}</a></li>
                </ul>
            </div>
        </div>

    </div>
@endsection