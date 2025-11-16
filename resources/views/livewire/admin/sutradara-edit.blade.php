@extends('admin.layouts.app')

@section('title', 'Edit Sutradara')

@section('content')
<div class="max-w-3xl mx-auto py-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Sutradara</h1>

    <form action="{{ route('admin.sutradara.update', $sutradara->id) }}"
          method="POST" enctype="multipart/form-data"
          class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700">Nama Sutradara</label>
            <input type="text" name="nama_sutradara"
                   value="{{ $sutradara->nama_sutradara }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm"
                   required>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700">Foto Profil Saat Ini</label>
            <img src="{{ $sutradara->foto_profil ? asset('storage/'.$sutradara->foto_profil) : asset('storage/default_profile.jpg') }}"
                 class="h-20 w-20 rounded-full mb-3 object-cover">

            <input type="file" name="foto_profil" class="w-full border-gray-300 rounded-lg shadow-sm">
        </div>

        <div class="mb-6">
            <label class="block mb-1 font-medium text-gray-700">Biografi</label>
            <textarea name="biografi" rows="5"
                      class="w-full border-gray-300 rounded-lg shadow-sm">{{ $sutradara->biografi }}</textarea>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('admin.sutradara.index') }}"
               class="px-4 py-2 bg-gray-200 rounded-lg mr-2">Batal</a>

            <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700">
                Perbarui
            </button>
        </div>
    </form>

</div>
@endsection
