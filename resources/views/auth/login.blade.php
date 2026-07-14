@extends('layouts.guest')

@section('content')
    {{-- Two self-contained login designs; the active one is chosen by
         config('app.login_layout') (LOGIN_LAYOUT in .env): "split" or "centered". --}}
    @if (config('app.login_layout') === 'centered')
        @include('auth.partials.centered')
    @else
        @include('auth.partials.split')
    @endif
@endsection
