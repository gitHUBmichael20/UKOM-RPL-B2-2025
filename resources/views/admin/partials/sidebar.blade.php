<aside id="sidebar"
       class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0 bg-gradient-to-b from-gray-900 to-gray-800 border-r border-gray-700">
  <div class="h-full px-3 py-4 overflow-y-auto">
    <!-- Logo -->
    <div class="flex items-center justify-between mb-6 px-3">
      <div class="flex items-center space-x-3">
        <img src="{{ asset('storage/logo.png') }}"
             width="60"
             alt="">
        <span class="text-xl font-bold text-white">Absolute Cinema</span>
      </div>
    </div>

    <!-- Navigation -->
    <ul class="space-y-2 font-medium">
      <!-- Dashboard -->
      <li>
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
          <i class="fa-solid fa-gauge w-5 text-gray-300"></i>
          <span class="ml-3">Dashboard</span>
        </a>
      </li>

      <!-- Films -->
      <li>
        <a href="#"
           class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
          <i class="fa-solid fa-film w-5 text-gray-300"></i>
          <span class="ml-3">Film</span>
        </a>
      </li>

      <!-- Jadwal Tayang -->
      <li>
        <a href="#"
           class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
          <i class="fa-solid fa-calendar-days w-5 text-gray-300"></i>
          <span class="ml-3">Jadwal Tayang</span>
        </a>
      </li>

      <!-- Studio -->
      <li>
        <a href="{{ route('admin.studio.index') }}"
           class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700 {{ request()->routeIs('admin.studio.*') ? 'bg-gray-700' : '' }}">
          <i class="fa-solid fa-video w-5 text-gray-300"></i>
          <span class="ml-3">Studio</span>
        </a>
      </li>

      <!-- Pemesanan -->
      <li>
        <a href="#"
           class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
          <i class="fa-solid fa-ticket w-5 text-gray-300"></i>
          <span class="ml-3">Pemesanan</span>
        </a>
      </li>

      @if(isRole('admin'))
        <!-- Divider -->
        <li class="pt-4 mt-4 space-y-2 border-t border-gray-700">
          <span class="text-xs text-gray-400 px-3 uppercase">Master Data</span>
        </li>

        <!-- Genre -->
        <li>
          <a href="#"
             class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
            <i class="fa-solid fa-tags w-5 text-gray-300"></i>
            <span class="ml-3">Genre</span>
          </a>
        </li>

        <!-- Sutradara -->
        <li>
          <a href="#"
             class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
            <i class="fa-solid fa-user-tie w-5 text-gray-300"></i>
            <span class="ml-3">Sutradara</span>
          </a>
        </li>

        <!-- Users -->
        <li>
          <a href="{{ route('admin.users.index') }}"
             class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">
            <i class="fa-solid fa-users w-5 text-gray-300"></i>
            <span class="ml-3">Users</span>
          </a>
        </li>

        <!-- Laporan -->
        <li>
          <a href="#"
             class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700">
            <i class="fa-solid fa-chart-column w-5 text-gray-300"></i>
            <span class="ml-3">Laporan</span>
          </a>
        </li>
      @endif

      <!-- Divider -->
      <li class="pt-4 mt-4 space-y-2 border-t border-gray-700">
        <span class="text-xs text-gray-400 px-3 uppercase">Settings</span>
      </li>

      <!-- Profile -->
      <li>
        <a href="{{ route('profile.edit') }}"
           class="flex items-center p-3 rounded-lg text-white hover:bg-gray-700 {{ request()->routeIs('profile.edit') ? 'bg-gray-700' : '' }}">
          <i class="fa-solid fa-user w-5 text-gray-300"></i>
          <span class="ml-3">Profile</span>
        </a>
      </li>
    </ul>
  </div>
</aside>