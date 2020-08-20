@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('game.genre_list') }}</h1>

    <hr>

    <div class="page-section">
        <table>
            <thead>
                <tr>
                    <th class="px-4">{{ __('game.genre_id') }}</th>
                    <th class="px-4">{{ __('game.genre_name') }}</th>
                </tr>
            </thead>
            @foreach ($genres as $genre)
                <tr class="my-3">
                    <td class="px-4">{{ $genre->id }}</td>
                    <td class="px-4">{{ $genre->name }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="w-1/4 mx-auto text-center">
        {{ $genres->links() }}
    </div>

    <div class="page-section">
        <a href="{{ route('acp') }}" class="button-secondary">{{ __('site.acp') }}</a>
    </div>
@endsection