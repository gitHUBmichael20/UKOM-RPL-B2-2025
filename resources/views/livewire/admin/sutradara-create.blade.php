@extends('admin.layouts.app')

@section('title', 'Tambah Sutradara')

@section('content')
    <!-- Header -->
    <div class="p-3">
        <div class="mb-6">
            <div class="flex items-center text-sm text-gray-600 mb-4">
                <a href="{{ route('admin.sutradara.index') }}"
                   class="hover:text-blue-600">Film</a>
                <i class="fa-solid fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-900">Tambah Sutradara</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Sutradara</h2>
            <p class="text-gray-600 mt-1">Isi form di bawah untuk menambahkan sutradara baru</p>
        </div>
    
        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">
            <form action="{{ route('admin.sutradara.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
    
                <!-- Foto Profil -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Foto Profil (Opsional)
                    </label>
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0">
                            <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center"
                                 id="preview-container">
                                <svg class="w-10 h-10 text-gray-400"
                                     fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file"
                                   name="foto_profil"
                                   id="foto_profil"
                                   accept="image/*"
                                   onchange="previewImage(event)"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF maksimal 2MB</p>
                        </div>
                    </div>
                    @error('foto_profil')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
    
                <!-- Nama Sutradara -->
                <div class="mb-4">
                    <label for="nama_sutradara"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Sutradara <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nama_sutradara"
                           name="nama_sutradara"
                           value="{{ old('nama_sutradara') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('nama_sutradara') border-red-500 @enderror"
                           required>
                    @error('nama_sutradara')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
    
                <!-- Biografi -->
                <div class="mb-6">
                    <label for="biografi"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Biografi
                    </label>
                    <textarea id="biografi"
                              name="biografi"
                              rows="5"
                              placeholder="Tulis biografi sutradara..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('biografi') border-red-500 @enderror">{{ old('biografi') }}</textarea>
                    @error('biografi')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
    
                <!-- Buttons -->
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-150">
                        Simpan Sutradara
                    </button>
                    <a href="{{ route('admin.sutradara.index') }}"
                       class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition duration-150">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const container = document.getElementById('preview-container');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    container.innerHTML = `<img src="${e.target.result}" class="h-20 w-20 rounded-full object-cover">`;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection