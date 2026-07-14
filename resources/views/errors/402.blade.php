@extends('layouts.guest')

@section('content')
    @include('errors.partials.error', [
        'code' => 402,
        'title' => 'Payment Required',
        'message' => 'Payment is required to access this resource.',
    ])
@endsection
