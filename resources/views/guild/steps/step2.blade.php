<h2 class="page-subheader">{{ __('guild.add_game') }}</h2>
<div class="page-section">
    <p>{!! __('guild.add_game_details') !!}</p>
</div>

<form action="{{ route('guild-create') }}" method="post">
    @csrf

    <input type="hidden" name="step" value="3" />

    <h2 class="page-subheader">{{ __('game.details') }}</h2>
    <div class="page-section">
        <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
            <label for="name" class="block text-cgwhite text-sm mb-2">
                {{ __('game.name') }}:
            </label>

            <input id="name" type="text" class="form-field w-full @error('name')  border-red-500 @enderror" name="name" @if (old('name')) value="{{ old('name') }}" @endif required autocomplete="off" autofocus>

            @error('name')
                <p class="text-red-500 text-xs italic mt-4">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <div class="page-section">
        <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
            <label for="genre" class="block text-cgwhite text-sm mb-2">
                {{ __('game.genre') }}:
            </label>

            <select name="genre" class="form-field w-full @error('genre') border-red-500 @enderror">
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}">@if ($genre->short_name == "Other") ===== {{ $genre->name }} ====== @else {{ $genre->short_name }} -- {{ $genre->name }} @endif</option>
                @endforeach
            </select>

            @error('genre')
                <p class="text-red-500 text-xs italic mt-4">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>


    <div class="page-section">
        <input type="submit" value="{{ __('guild.next_step') }}" class="button-primary">
        <a href="{{ route('home') }}" class="button-secondary">{{ __('site.cancel') }}</a>
    </div>
    
</form>