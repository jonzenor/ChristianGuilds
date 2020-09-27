@extends('layouts.guild')

@section('content')

        <div class="col-span-6 md:col-span-4 xl:col-span-5 row-span-2 h-auto">
            {{-- getGuildPage($guild->id, 'info') --}}
            <div>{!! $guild->description !!}</div>

            @can('manage-guild', $guild->id)
                <div class="page-section">
                    <p class="my-12"><a href="{{ route('guild-edit', $guild->id) }}" class="button-primary">{{ __('guild.edit') }}</a></p>
                </div>
            @endcan
    
        </div>

@endsection
