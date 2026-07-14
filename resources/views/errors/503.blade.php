@extends('layouts.guest')

@section('content')
    @include('errors.partials.error', [
        'code' => 503,
        'title' => 'Service Unavailable',
        'message' => 'We\'re down for maintenance right now. We\'ll be back shortly.',
    ])
@endsection
