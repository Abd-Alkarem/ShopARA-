<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component
{
    public array $selectedUsers = [];
    public bool $selectAll = false;
    
    /**
     * Get the count of selected users.
     */
    public function getSelectedCountProperty(): int
    {
        return count($this->selectedUsers);
    }
    
    /**
     * Get all users from the database except current user.
     */
    public function getUsers()
    {
        return User::select('id', 'name', 'email', 'created_at')
                   ->where('id', '!=', auth()->id())
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Delete selected users.
     */
    public function deleteSelectedUsers(): void
    {
        if (empty($this->selectedUsers)) {
            session()->flash('error', 'Please select at least one user to delete.');
            return;
        }

        $deletedCount = User::whereIn('id', $this->selectedUsers)->delete();
        
        session()->flash('message', "Successfully deleted {$deletedCount} user(s).");
        $this->selectedUsers = [];
        $this->selectAll = false;
    }

    /**
     * Updated selectedUsers property.
     */
    public function updatedSelectedUsers(): void
    {
        $allUsers = $this->getUsers();
        if (count($this->selectedUsers) === $allUsers->count()) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }

    /**
     * Updated selectAll property.
     */
    public function updatedSelectAll(): void
    {
        if ($this->selectAll) {
            $this->selectedUsers = $this->getUsers()->pluck('id')->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }
}; ?>

<div class="space-y-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ __('User Management') }}
                </h3>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-500">
                        {{ __('Total:') }} {{ $this->getUsers()->count() }} {{ __('accounts') }}
                    </div>
                    @if ($this->getUsers()->count() > 0)
                        <button 
                            wire:click="deleteSelectedUsers"
                            wire:confirm="Are you sure you want to delete the selected users? This action cannot be undone."
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ $this->selectedCount == 0 ? 'disabled' : '' }}
                        >
                            {{ __('Delete Selected') }} ({{ $this->selectedCount }})
                        </button>
                    @endif
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session()->has('message'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
                    <p class="text-sm text-green-800">{{ session('message') }}</p>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            @if ($this->getUsers()->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input 
                                        type="checkbox" 
                                        wire:model.live="selectAll"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    >
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Username') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Email') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Joined') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($this->getUsers() as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input 
                                            type="checkbox" 
                                            wire:model.live="selectedUsers"
                                            value="{{ $user->id }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        >
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No other accounts found') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('No other user accounts have been created yet.') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
