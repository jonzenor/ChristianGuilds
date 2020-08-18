@extends('layouts.email')

@section('content')
    <p>Attempted Forge Test Access/</p>

    <p>
        User Name: {{ $user->name }}
    </p>
@endsection