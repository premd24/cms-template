@extends('layouts.guest')

@section('content')
    @include('errors.partials.error', [
        'code' => 401,
        'title' => 'Unauthorized',
        'message' => 'You need to be signed in to view this page.',
    ])
@endsection
