@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('community.list') }}</h1>

    <hr>

    <div class="page-section">
        <table>
            <thead>
                <tr>
                    <th class="px-4">{{ __('community.id') }}</th>
                    <th class="px-4">{{ __('community.name') }}</th>
                    <th class="px-4">{{ __('community.owner') }}</th>
                    <th class="px-4">{{ __('community.date_created') }}</th>
                    <th class="px-4">{{ __('community.manage') }}</th>
                </tr>
            </thead>
            @foreach ($communities as $community)
                <tr class="my-3">
                    <td class="px-4">{{ $community->id }}</td>
                    <td class="px-4"><a href="{{ route('community', $community->id) }}" class="link">{{ \Illuminate\Support\Str::limit($community->name, config('site.truncate_length'), $end='...') }}</a></td>
                    <td class="px-4"><a href="{{ route('profile', $community->owner->id) }}" class="link">{{ $community->owner->name }}</a></td>
                    <td class="px-4">{{ ucwords($community->created_at) }}</td>
                    {{-- <td class="px-4"><a href="{{ route('community-edit', $community->id) }}" class="link"><i class="fal fa-edit"></i></a></td> --}}
                </tr>
            @endforeach
        </table>
    </div>

    <div class="w-1/4 mx-auto text-center">
        {{ $communities->links() }}
    </div>

    <div class="page-section">
        <a href="{{ route('acp') }}" class="button-secondary">{{ __('site.acp') }}</a>
    </div>
@endsection