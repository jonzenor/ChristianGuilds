@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $guild->name }}</h1>
    <h2 class="page-subheader">{{ $guild->game->name }}</h2>

    <hr />


@endsection
