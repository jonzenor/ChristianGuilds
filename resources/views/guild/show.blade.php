@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $guild->name }}</h1>
    <h2 class="page-subheader"><a href="{{ route('game', $guild->game_id) }}" class="link">{{ $guild->game->name }}</a> @if ($guild->game->status == "pending") <span class="bg-yellow-600 rounded-lg text-sm text-white px-4 py-1"><i class="fas fa-exclamation-circle text-lg"></i> {{ __('game.is_pending') }}</span> @endif</h2>
    
    @if ($guild->server) 
        <h3 class="page-subtitle">{{ __('guild.server') }}: {{ $guild->server->name }}</h3>
    @elseif ($guild->server_name)
        <h3 class="page-subtitle">{{ __('guild.server') }}: {{ $guild->server_name }}</h3>
    @endif

    @if ($guild->community)
        <h3 class="page-subtitle"><a href="{{ route('community', $guild->community->id) }}" class="link">{{ $guild->community->name }}</a> {{ __('guild.community_member') }}</h3>
    @endif

    <hr />

    <div class="grid grid-cols-6 gap-4 m-8">
        <div class="col-span-6 md:col-span-2 xl:col-span-1">
            Side Bar
        </div>

        <div class="col-span-6 md:col-span-4 xl:col-span-5 row-span-2 relative">
            {{-- getGuildPage($guild->id, 'info') --}}
            {!! $guild->description !!}

            @can('manage-guild', $guild->id)
                <div class="page-section bottom-0 absolute">
                    <a href="{{ route('guild-edit', $guild->id) }}" class="button-primary">{{ __('guild.edit') }}</a>
                </div>
            @endcan
    
        </div>

        <div class="col-span-6 md:col-span-2 xl:col-span-1">
            <h3 class="section-header">{{ __('guild.leaders') }}</h3>
            <ul>
                @foreach ($guild->members as $member)
                    @if ($member->pivot->position == "owner" || $member->pivot->position == "leader")
                        <li> @if ($member->pivot->position == "owner") <i class="fad fa-crown" title="{{ __('guild.owner') }}"></i> @endif <a href="{{ route('profile', $member->id) }}" class="link">{{ $member->name }}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>


@endsection
