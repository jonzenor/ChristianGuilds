@extends('layouts.main')

@section('content')

    <h1 class="page-header">{{ __('site.forge_permissions') }}</h1>
    <hr />

    <div class="page-section">
        @foreach ($roles as $role)
            <a href="{{ route('forge-grant', $role->id) }}" class="role-tag bg-{{ $role->color }}">Grant {{ $role->name }} Permissions</a>
        @endforeach
    </div>

    <div class="page-section text-center">
        <span class="highlight">** This page is for testing only. Use these permissions wisely to help test the site and open tickets on any issues/suggestions. **</span>
    </div>

@endsection