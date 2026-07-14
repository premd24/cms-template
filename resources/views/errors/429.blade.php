@extends('layouts.guest')

@section('content')
    @include('errors.partials.error', [
        'code' => 429,
        'title' => 'Too Many Requests',
        'message' => 'You\'ve made too many requests. Please slow down and try again shortly.',
    ])
@endsection
