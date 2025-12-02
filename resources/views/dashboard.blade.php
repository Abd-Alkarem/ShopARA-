<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">Welcome to Dashboard</h1>
                    
                    <!-- Simple Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <h3 class="font-semibold text-blue-900">Total Users</h3>
                            <p class="text-2xl font-bold text-blue-600">{{ App\Models\User::count() }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg">
                            <h3 class="font-semibold text-green-900">Total Products</h3>
                            <p class="text-2xl font-bold text-green-600">{{ App\Models\Product::count() }}</p>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-lg">
                            <h3 class="font-semibold text-purple-900">Your Cart Items</h3>
                            <p class="text-2xl font-bold text-purple-600">{{ auth()->user()->cartCount() }}</p>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="space-y-2">
                        <h2 class="text-lg font-semibold mb-3">Quick Actions</h2>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                View Users
                            </a>
                            <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Manage Products
                            </a>
                            <a href="{{ route('cart.index') }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                                View Cart
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
