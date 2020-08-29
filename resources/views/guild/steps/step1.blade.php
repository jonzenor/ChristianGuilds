<form action="{{ route('guild-create') }}" method="post">
    @csrf

    <input type="hidden" name="step" value="2" />

    <h2 class="page-subheader">{{ __('guild.game_select') }}</h2>
    <div class="page-section">
        <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
            <label for="game" class="block text-cgwhite text-sm mb-2">
                {{ __('guild.game') }}:
            </label>

            <select name="game" class="form-field w-full @error('genre') border-red-500 @enderror">
                @foreach ($games as $game)
                    <option value="{{ $game->id }}" @if (old('game') == $game->id) selected @endif>{{ $game->name }}</option>
                @endforeach
                <option value="0"> -- {{ __('game.not_listed') }} --</option>
            </select>

            @error('game')
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