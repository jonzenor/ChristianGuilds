@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('guild.list') }}</h1>

    <hr>

    <div class="page-section">
        <table>
            <thead>
                <tr>
                    <th class="px-4">{{ __('guild.id') }}</th>
                    <th class="px-4">{{ __('guild.name') }}</th>
                    <th class="px-4">{{ __('guild.game') }}</th>
                    <th class="px-4">{{ __('guild.owner') }}</th>
                    <th class="px-4">{{ __('guild.created') }}</th>
                    <th class="px-4">{{ __('guild.manage') }}</th>
                </tr>
            </thead>
            @foreach ($guilds as $guild)
                <tr class="my-3">
                    <td class="px-4">{{ $guild->id }}</td>
                    <td class="px-4"><a href="{{ route('guild', $guild->id) }}" class="link">{{ $guild->name }}</a></td>
                    <td class="px-4">{{ $guild->game->name }}</td>
                    <td class="px-4"><a href="{{ route('profile', $guild->owner->id) }}" class="link">{{ $guild->owner->name }}</a></td>
                    <td class="px-4">{{ ucwords($guild->created_at) }}</td>
                    <td class="px-4"><i class="fal fa-edit"></i></td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="w-1/4 mx-auto text-center">
        {{ $guilds->links() }}
    </div>

    <div class="page-section">
        <a href="{{ route('acp') }}" class="button-secondary">{{ __('site.acp') }}</a>
    </div>
@endsection