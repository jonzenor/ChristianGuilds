@extends('layouts.main')

@section('content')
    <h1 class="text-orange-500 text-4xl p-3">{{ $user->name }}</h1>

    <br />
    <div class="m-2">
        <h2 class="text-blue-700 font-bold text-lg my-3">{{ __('user.roles') }}</h2>
        @foreach ($user->role as $role)
            <span class="px-2 bg-{{ $role->color }} text-white rounded-full text-xs">{{ $role->name }}</span>
        @endforeach
    </div>

    <br />
    <form action="{{ route('add-role', $user->id) }}" method="post">
        @csrf

        <div class="w-full flex">
            <div class="w-1/2 p-4 md:w-1/5">
                <select name="role">
                    @foreach ($newRoles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-1/2 p-4">
                <div class="m-4 w-11/12 md:w-1/2">
                    <input type="submit" class="py-2 px-5 bg-orange-500 text-white font-bold rounded-full cursor-pointer border-orange-900" value="{{ __('user.add_role') }}">
                </div>
            </div>
        </div>
    </form>
@endsection