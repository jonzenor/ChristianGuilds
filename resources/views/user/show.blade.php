@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $user->name }}</h1>

    <hr />

    @if ($user->roles->count() >= 1)
        @can('view-roles')
            <h2 class="page-subheader">{{ __('user.roles') }}</h2>
            <div class="page-section">
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
        @endcan
    @endif

    @can('add-global-role')
        <h3 class="page-subheader-h3">{{ __('user.add_role') }}</h3>
        <div class="page-section">
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

@endsection