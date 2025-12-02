<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900">
            {{ __('Items') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <livewire:items />
    </div>
</x-app-layout>
