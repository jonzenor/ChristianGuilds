@extends('layouts.email')

@section('content')
    <p>A new guild was created on Christian Guilds.</p>

    <p>
        Guild: {{ $guild->name }}<br />
        Owner: {{ $guild->owner->name }}<br />
        Game: {{ $guild->game->name }}<br />
    </p>
@endsection