<nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
    <div class="px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <button id="sidebar-toggle" type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                <!-- Breadcrumb -->
                <div class="ml-3 hidden md:block">
                    <h1 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button type="button"
                    class="relative p-2 text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <i class="fa-solid fa-bell text-base"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <!-- User Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="flex items-center space-x-3 text-sm bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <div class="flex items-center space-x-2">
                            <img class="w-8 h-8 rounded-full" src="{{ auth()->user()->profile_photo_url ?? asset('storage/profile-photos/default_profile.jpg') }}" alt="user photo">
                            <div class="hidden md:block text-left">
                                <div class="font-medium text-gray-900">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-gray-500 text-xs"></i>
                        </div>
                    </button>

                    <!-- Dropdown menu -->
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fa-solid fa-user mr-2"></i>
                            Profile
                        </a>
                        <hr class="my-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <i class="fa-solid fa-right-from-bracket mr-2"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Alpine.js for dropdown -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
