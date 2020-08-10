@extends('layouts.main')

@section('content')
    <div id="admin-box" class="m-6 border border-gray-300 rounded-t-md w-full md:w-1/2 lg:w-1/4">
        <div class="bg-blue-50 text-blue-900 rounded-t-md text-lg font-bold text-center p-2">{{ __('user.users') }}</div>
        <div class="">
            <ul>
                @foreach ($users as $user)
                    <li class="m-3"> {{ $user->name }}
                @endforeach
            </ul>
            <br />
            <div class="w-full text-right m-2">
                <span class="font-bold p-8"> {{ __('user.count', ['count' => $userCount]) }}</span>
            </div>
        </div>
    </div>
@endsection