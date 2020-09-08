@extends('layouts.main')

@section('content')
    <h1 class="page-header">{{ __('community.create_title') }}</h1>

    <hr />
    <h2 class="section-header">{{ __('community.description_label') }}</h2>
    <div class="page-section">
        <p>{!! __('community.description_explain') !!}</p>
    </div>
    
    <form action="{{ route('community-create') }}" method="post">
        @csrf
    
        <input type="hidden" name="step" value="2" />
    
        <div class="page-section">
            <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
                <label for="name" class="block text-cgwhite text-sm mb-2">
                    {{ __('community.name') }}:
                </label>
    
                <input type="text" name="name" class="form-field w-full @error('name')  border-red-500 @enderror" name="name" @if (old('name')) value="{{ old('name') }}" @endif required>

                @error('name')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <div class="page-section">
            <div class="flex flex-wrap mb-6 md:w-1/2 lg:w-1/3">
                <label for="description" class="block text-cgwhite text-sm mb-2">
                    {{ __('community.description') }}:
                </label>
    
                <textarea name="description" class="form-field w-full @error('description')  border-red-500 @enderror" description="description">@if (old('description')) value="{{ old('description') }}" @endif</textarea>

                @error('description')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    
        <div class="page-section">
            <input type="submit" value="{{ __('community.create_yours') }}" class="button-primary">
            <a href="{{ route('home') }}" class="button-secondary">{{ __('site.cancel') }}</a>
        </div>
        
    </form>
@endsection
