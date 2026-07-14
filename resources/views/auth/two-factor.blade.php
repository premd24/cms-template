@extends('layouts.guest')

@section('content')
    {{-- Choose centered or split 2FA design depending on login layout configuration --}}
    @if (config('app.login_layout') === 'centered')
        @include('auth.partials.two-factor-centered')
    @else
        @include('auth.partials.two-factor-split')
    @endif
@endsection
