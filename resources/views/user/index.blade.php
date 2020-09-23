@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('user.list') }}</h1>

    <hr>

    <div class="page-section block md:hidden">
        @foreach ($users as $user)
            <h2 class="text-cggreen-500 text-3xl m-3 font-bold"><a href="{{ route('profile', $user->id) }}">{{ \Illuminate\Support\Str::limit($user->name, config('site.truncate_length'), $end='...') }}</a></h2>

            @if ($user->loginSecurity && $user->loginSecurity->google2fa_enable == 1)
                <i class="fas fa-shield-check text-green-600 text-2xl"></i>
            @else
                <i class="fas fa-shield text-red-600 text-2xl"></i>
            @endif

            @foreach ($user->roles as $role)
                <span class="bg-{{ $role->color }} role-tag">{{ $role->name }}</span>
            @endforeach

            <br />

            <span class="m-2"><i class="fal fa-calendar-plus highlight"></i> {{ $user->created_at }}</span><br />
            <span class="m-2"><i class="fal fa-calendar-edit highlight"></i> {{ $user->updated_at }}</span><br />

            <hr />
        @endforeach
    </div>

    <div class="page-section hidden md:block">
        <table>
            <thead>
                <tr>
                    <th class="px-4">{{ __('user.id') }}</th>
                    <th class="px-4">{{ __('user.name') }}</th>
                    <th class="px-4">{{ __('user.2fa_security') }}</th>
                    <th class="px-4">{{ __('user.account_created') }}</th>
                    <th class="px-4">{{ __('user.account_modified') }}</th>
                    <th class="px-4">{{ __('user.roles') }}</th>
                </tr>
            </thead>
            @foreach ($users as $user)
                <tr class="my-3">
                    <td class="px-4">{{ $user->id }}</td>
                    <td class="px-4"><a href="{{ route('profile', $user->id) }}" class="link">{{ \Illuminate\Support\Str::limit($user->name, config('site.truncate_length'), $end='...') }}</a></td>
                    <td class="px-4">
                        @if ($user->loginSecurity && $user->loginSecurity->google2fa_enable == 1)
                            <i class="fas fa-shield-check text-green-600 text-2xl"></i>
                        @else
                            <i class="fas fa-shield text-red-600 text-2xl"></i>
                        @endif
                    </td>
                    <td class="px-4">{{ $user->created_at }}</td>
                    <td class="px-4">{{ $user->updated_at }}</td>
                    <td class="px-4">
                        @foreach ($user->roles as $role)
                            <span class="bg-{{ $role->color }} role-tag">{{ $role->name }}</span>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="w-1/4 mx-auto text-center">
        {{ $users->links() }}
    </div>

    <div class="page-section">
        <a href="{{ route('acp') }}" class="button-secondary">{{ __('site.acp') }}</a>
    </div>

@endsection