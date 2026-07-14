@extends('layouts.guest')

@section('content')
    @include('errors.partials.error', [
        'code' => 403,
        'title' => 'Forbidden',
        'message' => 'You don\'t have permission to access this page.',
    ])
@endsection
