<section>
    <header>
        <h2 class="text-lg font-semibold text-gray-900">Pengaturan Akun</h2>
        <p class="mt-1 text-sm text-gray-600">
            Ubah informasi akun, termasuk nama, email, nomor telepon, dan foto profil.
        </p>
    </header>

    <!-- Form -->
    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Foto Profil -->
        <div>
            <x-input-label for="foto_profil" :value="__('Foto Profil')" />
            <div class="flex items-center gap-4 mt-2">
                <div class="flex-shrink-0">
                    <img id="previewImage"
                         src="{{ $user->profile_photo_url }}"
                         class="w-20 h-20 rounded-full object-cover border border-gray-300"
                         alt="Foto Profil">
                </div>
                <div class="flex-1">
                    <input type="file"
                           id="foto_profil"
                           name="foto_profil"
                           accept="image/*"
                           class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                  file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200
                                  focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                           onchange="previewProfileImage(event)">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG — Maks 2MB</p>

                    @if ($user->foto_profil)
                        <button type="button"
                                onclick="hapusFotoProfil()"
                                class="mt-2 text-sm text-red-600 hover:text-red-800">
                            Hapus Foto
                        </button>
                    @endif
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('foto_profil')" />
        </div>

        <!-- Nama -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text"
                          class="mt-1 block w-full"
                          :value="old('name', $user->name)"
                          required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email"
                          class="mt-1 block w-full"
                          :value="old('email', $user->email)"
                          required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- Nomor Telepon -->
        <div>
            <x-input-label for="phone" :value="__('Nomor Telepon')" />
            <x-text-input id="phone" name="phone" type="text"
                          class="mt-1 block w-full"
                          :value="old('phone', $user->phone)"
                          placeholder="08xxxxxxxxxx" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Tombol -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600">Perubahan disimpan.</p>
            @endif
        </div>
    </form>
</section>

<!-- Script Preview -->
<script>
    function previewProfileImage(event) {
        const input = event.target;
        const preview = document.getElementById('previewImage');
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = e => preview.src = e.target.result;
            reader.readAsDataURL(file);
        }
    }

    function hapusFotoProfil() {
        if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
            fetch("{{ route('profile.deletePhoto') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => location.reload());
        }
    }
</script>
