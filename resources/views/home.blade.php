@extends('layouts.app-b')

@php
    $user = Auth::user();
@endphp

@section('content')
    @if($user->hasRole(['Developer']))
        @include('ranks.developer.home')
    @elseif($user->hasRole(['Owner','Co-Owner']))

    @elseif($user->hasRole(['Administrator']))

    @elseif($user->hasRole(['General Moderator']))

    @elseif($user->hasRole(['Moderator']))

    @elseif($user->hasRole(['Disc Jockey']))

    @elseif($user->hasRole(['Pro Member']))

    @elseif($user->hasRole(['Member']))

    @elseif($user->can(['vip-access']))
    hi im vip
    @else
    <div class="my-3">
        <div class="callout callout-danger rounded-0">
            <h3>We are sorry!</h3>
            <p>
                But you are currently banned in using our feature. If you want to appeal. Contact us on our email at <a href="mailto:{{ env('APP_EMAIL','roldhandasalla27@gmail.com') }}" class="text-primary">{{ env('APP_EMAIL','roldhandasalla27@gmail.com') }}</a>
            </p>
        </div>
    </div>
    @endif
@endsection
