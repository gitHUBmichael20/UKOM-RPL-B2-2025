<aside id="sidebar"
       class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0 bg-gradient-to-b from-gray-900 to-gray-800 border-r border-gray-700">
  <div class="h-full px-3 py-4 overflow-y-auto">
    <!-- Logo -->
    <div class="flex items-center justify-between mb-6 px-3">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6 text-white"
               fill="currentColor"
               viewBox="0 0 20 20">
            <path
                  d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
            </path>
          </svg>
        </div>
        <span class="text-xl font-bold text-white">Absolute Cinema</span>
      </div>
    </div>

    <!-- Navigation -->
    <ul class="space-y-2 font-medium">
      <!-- Dashboard -->
      <li>
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
          <svg class="w-5 h-5"
               fill="currentColor"
               viewBox="0 0 20 20">
            <path
                  d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
            </path>
          </svg>
          <span class="ml-3">Dashboard</span>
        </a>
      </li>

      <!-- Films -->
      <li>
        <a href="#"
           class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
          <svg class="w-5 h-5"
               fill="currentColor"
               viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                  d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                  clip-rule="evenodd"></path>
          </svg>
          <span class="ml-3">Film</span>
        </a>
      </li>

      <!-- Jadwal Tayang -->
      <li>
        <a href="#"
           class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
          <svg class="w-5 h-5"
               fill="currentColor"
               viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                  d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                  clip-rule="evenodd"></path>
          </svg>
          <span class="ml-3">Jadwal Tayang</span>
        </a>
      </li>

      <!-- Studio -->
      <li>
        <a href="#"
           class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
          <svg class="w-5 h-5"
               fill="currentColor"
               viewBox="0 0 20 20">
            <path
                  d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z">
            </path>
          </svg>
          <span class="ml-3">Studio</span>
        </a>
      </li>

      <!-- Pemesanan -->
      <li>
        <a href="#"
           class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
          <svg class="w-5 h-5"
               fill="currentColor"
               viewBox="0 0 20 20">
            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
            <path fill-rule="evenodd"
                  d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                  clip-rule="evenodd"></path>
          </svg>
          <span class="ml-3">Pemesanan</span>
        </a>
      </li>

      @if(auth()->user()->role === 'admin')
        <!-- Divider -->
        <li class="pt-4 mt-4 space-y-2 border-t border-gray-700">
          <span class="text-xs text-gray-400 px-3 uppercase">Master Data</span>
        </li>

        <!-- Genre -->
        <li>
          <a href="#"
             class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
            <svg class="w-5 h-5"
                 fill="currentColor"
                 viewBox="0 0 20 20">
              <path
                    d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z">
              </path>
            </svg>
            <span class="ml-3">Genre</span>
          </a>
        </li>

        <!-- Sutradara -->
        <li>
          <a href="#"
             class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
            <svg class="w-5 h-5"
                 fill="currentColor"
                 viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                    d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="ml-3">Sutradara</span>
          </a>
        </li>

        <!-- Users -->
        <li>
          <a href="{{ route('admin.users.index') }}"
             class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">
            <svg class="w-5 h-5"
                 fill="currentColor"
                 viewBox="0 0 20 20">
              <path
                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
              </path>
            </svg>
            <span class="ml-3">Users</span>
          </a>
        </li>

        <!-- Laporan -->
        <li>
          <a href="#"
             class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
            <svg class="w-5 h-5"
                 fill="currentColor"
                 viewBox="0 0 20 20">
              <path
                    d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z">
              </path>
            </svg>
            <span class="ml-3">Laporan</span>
          </a>
        </li>
      @endif
      <!-- Divider -->
      <li class="pt-4 mt-4 space-y-2 border-t border-gray-700">
        <span class="text-xs text-gray-400 px-3 uppercase">Settings</span>
      </li>
      <li>
        <a href="{{ route('profile.edit') }}"
           class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
          <svg class="w-4 h-4 mr-2"
               fill="currentColor"
               viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                  d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                  clip-rule="evenodd"></path>
          </svg>
          Profile
        </a>
      </li>
    </ul>
  </div>
</aside>