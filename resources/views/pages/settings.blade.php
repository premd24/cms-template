@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Settings" description="Manage your credentials, active sessions, and workspace details." />

        <div class="grid grid-cols-12 gap-6">
            
            <!-- Sidebar Navigation Menu -->
            <div class="col-span-12 lg:col-span-3 space-y-2">
                <x-ui.card class="p-4 space-y-1">
                    <a href="{{ route('pages.settings') }}" 
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all text-left group {{ request()->routeIs('pages.settings') ? 'bg-primary-500 text-black font-extrabold' : 'text-gray-600 hover:bg-gray-50 font-bold' }}">
                        <x-heroicon-o-user class="h-5 w-5 shrink-0 transition-transform group-hover:scale-110" />
                        <span>Profile Settings</span>
                    </a>
                    
                    <a href="{{ route('pages.settings.password.show') }}" 
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all text-left group {{ request()->routeIs('pages.settings.password.show') ? 'bg-primary-500 text-black font-extrabold' : 'text-gray-600 hover:bg-gray-50 font-bold' }}">
                        <x-heroicon-o-key class="h-5 w-5 shrink-0 transition-transform group-hover:scale-110" />
                        <span>Change Password</span>
                    </a>
                    

                    
                    <a href="{{ route('pages.settings.sessions.show') }}" 
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all text-left group {{ request()->routeIs('pages.settings.sessions.show') ? 'bg-primary-500 text-black font-extrabold' : 'text-gray-600 hover:bg-gray-50 font-bold' }}">
                        <x-heroicon-o-shield-check class="h-5 w-5 shrink-0 transition-transform group-hover:scale-110" />
                        <span>Active Sessions</span>
                    </a>
                    
                    <a href="{{ route('pages.settings.system.show') }}" 
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all text-left group {{ request()->routeIs('pages.settings.system.show') ? 'bg-primary-500 text-black font-extrabold' : 'text-gray-600 hover:bg-gray-50 font-bold' }}">
                        <x-heroicon-o-cpu-chip class="h-5 w-5 shrink-0 transition-transform group-hover:scale-110" />
                        <span>System & Info</span>
                    </a>
                    

                </x-ui.card>
            </div>

            <!-- Main Panels -->
            <div class="col-span-12 lg:col-span-9">
                
                @if (request()->routeIs('pages.settings'))
                    <div class="space-y-6">
                        @include('pages.settings.partials.profile')
                    </div>
                @elseif (request()->routeIs('pages.settings.password.show'))
                    <div class="space-y-6">
                        @include('pages.settings.partials.password')
                    </div>

                @elseif (request()->routeIs('pages.settings.sessions.show'))
                    <div class="space-y-6">
                        @include('pages.settings.partials.sessions')
                    </div>

                @elseif (request()->routeIs('pages.settings.system.show'))
                    <div class="space-y-6">
                        @include('pages.settings.partials.system')
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection