{{-- BOTTOM NAVIGATION MOBILE --}}
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 lg:hidden shadow-2xl">
    <div class="flex justify-around items-stretch h-16">
        <!-- Home -->
        <a href="{{ route('dashboard') }}"
           class="flex flex-col items-center justify-center flex-1 gap-1 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500' }} hover:text-blue-600 transition">
            <i class="fas fa-home text-2xl"></i>
            <span class="text-xs font-medium">Home</span>
        </a>

        <!-- Tiket Saya -->
        <a href="{{ route('pemesanan.my-bookings') }}"
           class="flex flex-col items-center justify-center flex-1 gap-1 {{ request()->routeIs('pemesanan.my-bookings') ? 'text-blue-600' : 'text-gray-500' }} hover:text-blue-600 transition">
            <i class="fas fa-ticket-alt text-2xl"></i>
            <span class="text-xs font-medium">Tiket Saya</span>
        </a>

        <!-- Profil / Login -->
        @auth
            <a href="{{ route('profile.edit') }}"
               class="flex flex-col items-center justify-center flex-1 gap-1 {{ request()->routeIs('profile.*') ? 'text-blue-600' : 'text-gray-500' }} hover:text-blue-600 transition">
                <img src="{{ Auth::user()->profile_photo_url ?? asset('storage/default_profile.jpg') }}"
                     alt="Profil"
                     class="w-6 h-6 rounded-full object-cover">
                <span class="text-xs font-medium">Profil</span>
            </a>
        @else
            <a href="{{ route('login') }}"
               class="flex flex-col items-center justify-center flex-1 gap-1 text-gray-500 hover:text-blue-600 transition">
                <i class="fas fa-sign-in-alt text-2xl"></i>
                <span class="text-xs font-medium">Masuk</span>
            </a>
        @endauth
    </div>
</div>

{{-- TOP NAVBAR DESKTOP --}}
<nav class="bg-white border-b border-gray-100 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Logo + Menu Kiri (Desktop) -->
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Menu Home & Tiket Saya (hanya desktop) -->
                    <div class="hidden lg:flex items-center ml-10 space-x-8">
                        <x-nav-link :href="route('dashboard')"
                                    :active="request()->routeIs('dashboard')">
                            Home
                        </x-nav-link>
                        <x-nav-link :href="route('pemesanan.my-bookings')"
                                    :active="request()->routeIs('pemesanan.my-bookings')">
                            Tiket Saya
                        </x-nav-link>
                    </div>
            </div>

            <!-- Profile Dropdown atau Login (Desktop) -->
            <div class="hidden lg:flex items-center space-x-6">
                @auth
                    <x-dropdown align="right"
                                width="48">
                        <x-slot name="trigger">
                            <button
                                    class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition gap-2">
                                <img class="w-8 h-8 rounded-full object-cover"
                                     src="{{ Auth::user()->profile_photo_url }}"
                                     alt="Profil">
                                <div>{{ Auth::user()->name }}</div>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profil
                            </x-dropdown-link>
                            <form method="POST"
                                  action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    Keluar
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm font-medium text-gray-700 hover:text-gray-900">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="ml-4 text-sm font-medium text-gray-700 hover:text-gray-900">Daftar</a>
                    @endif
                @endauth
            </div>

        </div>
    </div>
</nav>