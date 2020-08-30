@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('game.genre_list') }}</h1>

    <hr>

    <div class="page-section">
        <table>
            <thead>
                <tr>
                    <th class="px-4">{{ __('game.genre_id') }}</th>
                    <th class="px-4">{{ __('game.genre_short_name') }}</th>
                    <th class="px-4">{{ __('game.genre_name') }}</th>
                    <th class="px-4">{{ __('game.genre_manage') }}</th>
                </tr>
            </thead>
            @foreach ($genres as $genre)
                @if ($genre->short_name != "Other")
                    <tr class="my-3">
                        <td class="px-4">{{ $genre->id }}</td>
                        <td class="px-4">{{ $genre->short_name }}</td>
                        <td class="px-4">{{ $genre->name }}</td>
                        <td class="px-4"><a href="{{ route('genre-edit', $genre->id) }}" class="link" alt="{{ __('game.edit_genre') }}"><i class="fal fa-edit"></i></a>
                    </tr>
                @endif
            @endforeach
        </table>
    </div>

    <div class="w-1/4 mx-auto text-center">
        {{ $genres->links() }}
    </div>

    <div class="page-section">
        <a href="{{ route('genre-add') }}" class="button-primary">{{ __('game.genre_add') }}</a>
        <a href="{{ route('acp') }}" class="button-secondary">{{ __('site.acp') }}</a>
    </div>
@endsection