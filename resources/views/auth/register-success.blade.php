<x-guest-layout title="ShopARA-Success">
    <div class="flex items-center justify-center min-h-screen">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-8 text-center">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <!-- Success Message -->
            <h2 class="text-2xl font-bold text-green-600 mb-2">
                Registration Successful!
            </h2>
            
            <p class="text-gray-600 mb-6">
                Your account has been created successfully. Redirecting to login...
            </p>

            <!-- Quick Redirect Indicator -->
            <div class="mb-6">
                <div class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-200 rounded-md">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm text-green-800 font-medium">
                        Redirecting momentarily...
                    </span>
                </div>
            </div>

            <!-- Manual Redirect Link -->
            <div class="text-sm">
                <span class="text-gray-500">Taking too long?</span>
                <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-500 ml-1">
                    Go to login now
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript for quick redirect (0.2 seconds) -->
    <script>
        // Redirect after 200 milliseconds (0.2 seconds)
        setTimeout(() => {
            window.location.href = '{{ route("login") }}';
        }, 200);
    </script>
</x-guest-layout>
