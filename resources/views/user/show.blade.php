@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $user->name }}</h1>

    <hr />

    <h2 class="page-subheader">{{ __('user.roles') }}</h2>
    <div class="page-section">
        @foreach ($user->roles as $role)
            <span class="px-2 my-2 bg-{{ $role->color }} text-white rounded-full text-xs"><a href="{{ route('remove-role', ['id' => $user->id, 'role' => $role->id]) }}">{{ $role->name }} <i class="fal fa-times-circle ml-2"></i></a></span>
        @endforeach      
    </div>

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


@endsection