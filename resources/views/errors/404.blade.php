@extends('layouts.guest')

@section('content')
    @include('errors.partials.error', [
        'code' => 404,
        'title' => 'Page Not Found',
        'message' => 'The page you\'re looking for doesn\'t exist or has been moved.',
    ])
@endsection
