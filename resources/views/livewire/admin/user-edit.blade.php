<div>
    @section('title', 'Edit User')

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('admin.users.index') }}"
               class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Edit User</h1>
        </div>
        <p class="text-gray-600 ml-10">Ubah data user yang sudah ada</p>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg max-w-2xl">
            {{ session('message') }}
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">
        <form wire:submit.prevent="update">
            <!-- Foto Profil -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Foto Profil
                </label>
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        @if ($foto_profil)
                            <img src="{{ $foto_profil->temporaryUrl() }}"
                                 class="h-20 w-20 rounded-full object-cover">
                        @elseif($current_foto)
                            <img src="{{ asset('storage/' . $current_foto) }}"
                                 class="h-20 w-20 rounded-full object-cover">
                        @else
                            <img src="{{ asset('storage/default_profile.jpg') }}"
                                 class="h-20 w-20 rounded-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file"
                               wire:model="foto_profil"
                               accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF maksimal 2MB</p>
                        @if($current_foto)
                            <button type="button"
                                    wire:click="deletePhoto"
                                    wire:confirm="Apakah Anda yakin ingin menghapus foto profil?"
                                    class="mt-2 text-sm text-red-600 hover:text-red-800">
                                Hapus Foto
                            </button>
                        @endif
                    </div>
                </div>
                @error('foto_profil') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Nama -->
            <div class="mb-4">
                <label for="name"
                       class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="name"
                       wire:model="name"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('name') border-red-500 @enderror">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email"
                       class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email"
                       id="email"
                       wire:model="email"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('email') border-red-500 @enderror">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Telepon -->
            <div class="mb-4">
                <label for="phone"
                       class="block text-sm font-medium text-gray-700 mb-2">
                    Telepon
                </label>
                <input type="text"
                       id="phone"
                       wire:model="phone"
                       placeholder="08xxxxxxxxxx"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role"
                       class="block text-sm font-medium text-gray-700 mb-2">
                    Role <span class="text-red-500">*</span>
                </label>
                <select id="role"
                        wire:model="role"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('role') border-red-500 @enderror">
                    <option value="pelanggan">Pelanggan</option>
                    <option value="kasir">Kasir</option>
                    <option value="admin">Admin</option>
                </select>
                @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Password Section -->
            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-700 mb-3">
                    Ubah Password (Kosongkan jika tidak ingin mengubah)
                </p>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru
                    </label>
                    <input type="password"
                           id="password"
                           wire:model="password"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('password') border-red-500 @enderror">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password"
                           id="password_confirmation"
                           wire:model="password_confirmation"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-150">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition duration-150">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>