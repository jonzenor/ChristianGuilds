@extends('layouts.main')

@section('content')

    <div class="page-section">

        <div class="flex items-center">
            <div class="w-full md:w-3/4 xl:w-2/3 md:mx-auto">

                <div class="flex flex-col break-words bg-cggray-700 border border-2 rounded shadow-md">

                    <div class="font-semibold bg-cggray-900 text-purple-500 py-3 px-6 mb-0 text-xl text-center">
                        {{ __('site.mission_title') }}
                    </div>

                    <div class="w-full p-6">
                        <p class="text-cgwhite">
                            {{ __('site.mission') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section text-center">
        <a href="{{ route('guild-create') }}" class="button-primary">{{ __('guild.create') }}</a>
    </div>

    @endsection
