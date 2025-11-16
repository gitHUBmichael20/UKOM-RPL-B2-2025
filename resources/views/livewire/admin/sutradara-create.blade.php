@extends('admin.layouts.app')

@section('title', 'Tambah Sutradara')

@section('content')
    <div class="max-w-3xl mx-auto py-8">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Sutradara</h1>

        <form action="{{ route('admin.sutradara.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white shadow rounded-lg p-6">
            @csrf

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Nama Sutradara</label>
                <input type="text" name="nama_sutradara" class="w-full border-gray-300 rounded-lg shadow-sm" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Foto Profil</label>
                <input type="file" name="foto_profil" class="w-full border-gray-300 rounded-lg shadow-sm">
            </div>

            <div class="mb-6">
                <label class="block mb-1 font-medium text-gray-700">Biografi</label>
                <textarea name="biografi" rows="5" class="w-full border-gray-300 rounded-lg shadow-sm"></textarea>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.sutradara.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg mr-2">Batal</a>

                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700">
                    Simpan
                </button>
            </div>
        </form>

    </div>
@endsection
