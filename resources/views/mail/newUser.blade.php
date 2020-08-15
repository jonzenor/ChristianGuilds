@extends('layouts.email')

@section('content')
    <p>A new user has registered on the site.</p>

    <p>
        User Name: {{ $user->name }}
    </p>
@endsection