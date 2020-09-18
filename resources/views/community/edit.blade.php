@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('community.edit_title') }} - {{ $community->name }}</h1>

    <hr />
    
    <form action="{{ route('community-update', $community->id) }}" method="post">
        @csrf

        <input type="hidden" name="step" value="2" />
        
        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
            <div class="page-section">
                <h3 class="section-header">{{ __('community.details') }}</h3>
                <div class="flex flex-wrap">
                    <label for="name" class="block text-cgwhite text-sm mb-2">
                        {{ __('community.name') }}:
                    </label>
        
                    <input type="text" name="name" class="form-field w-full @error('name') border-red-500 @enderror" name="name" @if (old('name')) value="{{ old('name') }}" @else value="{{ $community->name }}" @endif required>

                    @error('name')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <br />

                <div class="flex flex-wrap">
                    <label for="description" class="block text-cgwhite text-sm mb-2">
                        {{ __('community.description') }}:
                    </label>
        
                    <textarea name="description" class="form-field w-full @error('description') border-red-500 @enderror" description="description">@if (old('description')){{ old('description') }}@else{{ $community->description }}@endif</textarea>

                    @error('description')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <br />

                <input type="submit" value="{{ __('community.update_button') }}" class="button-primary">
                <a href="{{ route('community', $community->id) }}" class="button-secondary">{{ __('site.cancel') }}</a>
            </div>

            <div class="page-section">
                <h3 class="section-header">{{ __('community.guilds') }}</h3>

                <div>
                    <ul>
                        @foreach ($community->invites as $invite)
                            @if ($invite->guild_id)
                                <li> <a href="{{ route('guild', $invite->guild->id) }}" class="link">{{ $invite->guild->name }}</a> </li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <br />

                <div>
                    <h3 class="section-subheader">{{ __('community.invites') }}</h3>
                    <p>{!! __('community.invites_explain') !!}</p>

                    <ul>
                        @foreach ($community->invites as $invite)
                            @if (!$invite->guild_id)
                                <li> <span class="text-cool-gray-400">{{ $invite->code }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                
            </div>
        </div>
        
    </form>
@endsection
