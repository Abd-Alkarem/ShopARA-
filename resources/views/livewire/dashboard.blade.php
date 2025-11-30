<?php

use Livewire\Volt\Component;

new class extends Component
{
    public string $activeTab = 'dashboard';
}; ?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tab Navigation -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button 
                            wire:click="$set('activeTab', 'dashboard')"
                            class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'dashboard' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                        >
                            {{ __('Dashboard') }}
                        </button>
                        <button 
                            wire:click="$set('activeTab', 'users')"
                            class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'users' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                        >
                            {{ __('Users') }}
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Tab Content -->
            <div>
                <!-- Dashboard Tab -->
                @if($activeTab === 'dashboard')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            {{ __("You're logged in!") }}
                        </div>
                    </div>
                @endif

                <!-- Users Tab -->
                @if($activeTab === 'users')
                    <livewire:dashboard.user-list />
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
