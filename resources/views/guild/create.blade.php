@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('guild.create') }}</h1>

    <hr />

    @if ($step == 1)
        @include('guild.steps.step1')
    @endif

    @if ($step == 2)
        @include('guild.steps.step2')
    @endif

@endsection