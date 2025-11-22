{{-- GANTI SELURUH NAVBAR LAMA DENGAN INI --}}
{{-- resources/views/components/application-nav.blade.php atau di layout utamamu --}}

{{-- BOTTOM NAVIGATION MOBILE - Fixed di bawah (hanya muncul di hp) --}}
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
                <i class="fas fa-user text-2xl"></i>
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

{{-- TOP NAVBAR - Hanya muncul di tablet ke atas (lg+) --}}
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
                @auth
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
                @endauth
            </div>

            <!-- Profile Dropdown atau Login (Desktop) -->
            <div class="hidden lg:flex items-center space-x-6">
                @auth
                    <x-dropdown align="right"
                                width="48">
                        <x-slot name="trigger">
                            <button
                                    class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>
                                <svg class="ml-2 h-4 w-4"
                                     fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd" />
                                </svg>
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