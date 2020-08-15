@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('site.global_roles') }}</h1>

    <hr class="mb-6">

    @foreach ($roles as $role)
        <span class="text-{{ $role->color }} text-2xl ml-6">{{ $role->name }}</span>
        <div class="page-section m-2">
            <ul>
                @foreach ($role->users as $user)
                    <li> <a href="{{ route('profile', $user->id) }}" class="link">{{ $user->name }}</a>
                @endforeach
            </ul>
        </div>
    @endforeach

@endsection