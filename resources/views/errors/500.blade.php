@extends('layouts.guest')

@section('content')
    @include('errors.partials.error', [
        'code' => 500,
        'title' => 'Server Error',
        'message' => 'Something went wrong on our end. Please try again in a little while.',
    ])
@endsection
