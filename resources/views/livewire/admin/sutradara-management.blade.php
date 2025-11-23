@extends('admin.layouts.app')
@section('title', 'Manajemen Sutradara')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Sutradara</h2>
        <p class="text-gray-600 mt-1">Kelola data sutradara dengan mudah dan cepat.</p>
    </div>

    <!-- Header Actions -->
    <div class="flex justify-end mb-4">
        @if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.sutradara.create') }}"
       class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow">
        + Tambah Sutradara
    </a>
@endif
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-600 text-sm">
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Foto</th>
                        <th class="px-6 py-3">Biografi</th>
                         @if(auth()->user()->role === 'admin')
            <th class="px-6 py-3 text-right">Aksi</th>
        @endif
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 text-gray-800">
                    @forelse ($sutradara as $s)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $s->nama_sutradara }}</td>

                            <td class="px-6 py-4">
                                <img src="{{ $s->foto_profil ? asset('storage/' . $s->foto_profil) : asset('storage/default_profile.jpg') }}"
                                    class="h-12 w-12 rounded-full object-cover" alt="foto {{ $s->nama_sutradara }}">
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ Str::limit($s->biografi, 60) }}
                            </td>

                            <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium">

    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.sutradara.edit', $s->id) }}"
           class="text-blue-600 hover:text-blue-800 mr-3">
            Edit
        </a>

        <form action="{{ route('admin.sutradara.destroy', $s->id) }}"
              method="POST"
              class="inline-block"
              onsubmit="return confirm('Hapus sutradara ini?')">
            @csrf
            @method('DELETE')
            <button class="text-red-600 hover:text-red-800">Hapus</button>
        </form>
    @endif

</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                Tidak ada data sutradara.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($sutradara->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $sutradara->links() }}
            </div>
        @endif
    </div>
</div>
@endsection