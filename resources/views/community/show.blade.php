@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ $community->name }}</h1>
    
    <hr />

    <div class="grid grid-cols-6 gap-4 m-8">
        <div class="col-span-6 md:col-span-2 xl:col-span-2">
            Side Bar
        </div>

        <div class="col-span-6 md:col-span-4 xl:col-span-4 row-span-2 relative">
            <p>
                {!! $community->description !!}
            </p>

            @can('manage-community', $community->id)
                <div class="page-section bottom-0 absolute">
                    <a href="{{ route('community-edit', $community->id) }}" class="button-primary">{{ __('community.edit') }}</a>
                </div>
            @endcan

            <br /><br /><br />
        </div>

        <div class="col-span-6 md:col-span-2 xl:col-span-2">
            <h3 class="section-header">{{ __('community.guilds') }}</h3>
            <ul>
                @foreach($community->invites as $invite)
                    @if ($invite->guild)    
                        <li> <a href="{{ route('guild', $invite->guild->id) }}" class="link">{{ $invite->guild->name }}</a> </li>
                    @endif
                @endforeach
            </ul>
            
            <h3 class="section-header">{{ __('community.leaders') }}</h3>
            <ul>
                @foreach ($community->members as $member)
                    @if ($member->pivot->position == "owner" || $member->pivot->position == "leader")
                        <li> @if ($member->pivot->position == "owner") <i class="fad fa-crown" title="{{ __('community.owner') }}"></i> @endif <a href="{{ route('profile', $member->id) }}" class="link">{{ $member->name }}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>


@endsection
