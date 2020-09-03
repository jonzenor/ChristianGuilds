@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('site.page_not_found') }}</h1>

    <hr />

    <div class="page-section">
        {!! __('site.404_message') !!}
    </div>

@endsection