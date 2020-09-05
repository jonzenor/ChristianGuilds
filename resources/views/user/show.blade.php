@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $user->name }}</h1>

    <h2 class="page-subheader">{{ __('user.profile') }}</h2>
    <hr />

    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">

        @can('view-pii', $user->id)
            <div class="page-section">
                <h3 class="section-header">{{ __('user.profile_info') }}</h3>
                <div class="text-sm">{{ __('user.email') }}</div>
                {{ $user->email }}                
            </div>
        @endcan
        
        @can('view-user-security', $user)
            <div class="page-section">
                <h3 class="section-header">{{ __('user.security') }}</h3>
                <h3 class="section-subheader">{{ __('user.security_status') }}</h3>
                @if ($user->loginSecurity && $user->loginSecurity->google2fa_enable == 1)
                    <i class="fas fa-shield-check text-green-600 text-4xl"></i> {{ __('site.enabled') }}
                    @can('is-self', $user) <span class="ml-4"><a href="{{ route('2fa-settings') }}" class="link">[ {{ __('user.disable_2fa') }} ]</a>@endcan
                @else
                    <i class="fas fa-shield text-red-600 text-4xl"></i> {{ __('site.disabled') }}
                    @can('is-self', $user)<span class="ml-4"><a href="{{ route('2fa-settings') }}" class="link">[ {{ __('user.enable_2fa') }} ]</a>@endcan
                @endif

            </div>

        @endcan

        @if ($user->roles->count() >= 1)
            @can('view-roles')
                <div>
                    <div class="page-section">
                        <h3 class="section-header">{{ __('user.roles') }}</h3>
                        @foreach ($user->roles as $role)
                            <span class="bg-{{ $role->color }} role-tag">
                                @can('manage-user-roles')
                                    <a href="{{ route('remove-role', ['id' => $user->id, 'role' => $role->id]) }}">{{ $role->name }} <i class="fal fa-times-circle ml-2"></i></a>
                                @else
                                    {{ $role->name }}
                                @endcan
                            </span>
                        @endforeach
                    </div>

                    @can('add-global-role')
                        <div class="page-section">
                            <h3 class="section-subheader">{{ __('user.add_role') }}</h3>
                            <form action="{{ route('add-role', $user->id) }}" method="post">
                                @csrf
                
                                <div class="form-row">
                                    <select name="role" class="form-field">
                                        @foreach ($newRoles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">
                                    <input type="submit" class="button-primary" value="{{ __('user.add_role') }}">
                                </div>
                            </form>
                        </div>
                    @endcan

                </div>
            @endcan
        @endif

    </div>

    </div>






    @can('edit-user', $user->id)
        <div class="page-section">
            <a href="{{ route('profile-edit', $user->id) }}" class="button-primary">{{ __('user.edit_profile') }}</a>
        </div>
    @endcan

@endsection
