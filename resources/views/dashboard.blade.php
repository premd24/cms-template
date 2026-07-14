@extends('layouts.app')

@section('content')
    <x-ui.page-header title="Dashboard" description="Welcome back, {{ auth()->user()->name }}! This is your reusable template boilerplate." />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-ui.stat-card 
            label="Sample Items" 
            value="{{ $sampleItemsCount }}" 
            icon="heroicon-o-archive-box" 
            variant="primary" 
            href="{{ route('pages.sample-items') }}"
        />
    </div>

    <x-ui.card class="p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-2">Welcome to your new starter template!</h3>
        <p class="text-sm text-gray-500 leading-relaxed font-medium">
            This boilerplate contains reusable UI components, authentication, a generic user profile settings panel, 
            and a clean layouts architecture. Use this as a solid foundation to build any custom Laravel application.
        </p>
    </x-ui.card>
@endsection