<h2 class="page-subheader">{{ __('guild.game_selected') }}</h2>
<div class="page-section">
    <h3 class="page-subheader-h3">{{ $game->name }}</h3>
</div>

<form action="{{ route('guild-create') }}" method="post">
    @csrf

    <input type="hidden" name="step" value="3" />
    <input type="hidden" name="game" value="{{ $game->id }}">

    <h2 class="page-subheader">{{ __('guild.details') }}</h2>
    <div class="page-section">
        <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
            <label for="name" class="block text-cgwhite text-sm mb-2">
                {{ __('guild.name') }}:
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
        <input type="submit" value="{{ __('guild.complete_steps') }}" class="button-primary">
        <a href="{{ route('home') }}" class="button-secondary">{{ __('site.cancel') }}</a>
    </div>
    
</form>