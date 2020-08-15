@extends('layouts.main')

@section('content')

    <h1 class="page-header">{{ __('site.admin_control_panel') }}</h1>
    <hr />

    <h2 class="page-subheader">{{ __('user.users') }}</h2>
    <div class="page-section">
        <ul>
            @foreach ($users as $user)
                <li class="list-item"> <a href="{{ route('profile', $user->id) }}" class="link"> <i class="fal fa-user px-2"></i> {{ $user->name }}</a>
            @endforeach
        </ul>
    </div>

    <div class="page-section">
        <i class="fal fa-users"></i> <span class="highlight"> {{ __('user.count', ['count' => $userCount]) }}</span> [ <a href="{{ route('user-list') }}" class="link">{{ __('user.view_all') }}</a> ]
    </div>

@endsection