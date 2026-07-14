@extends('layouts.guest')

@section('content')
    @include('errors.partials.error', [
        'code' => 419,
        'title' => 'Page Expired',
        'message' => 'Your session has expired. Please refresh the page and try again.',
    ])
@endsection
