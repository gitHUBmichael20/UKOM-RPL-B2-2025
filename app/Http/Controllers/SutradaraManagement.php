<?php

namespace App\Http\Controllers;

use App\Models\Sutradara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SutradaraManagement extends Controller
{
    public function index()
    {
        $sutradara = Sutradara::latest()->paginate(10);
        return view('livewire.admin.sutradara-management', compact('sutradara'));
    }

    public function create()
    {
        return view('livewire.admin.sutradara-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sutradara' => 'required|string|max:100',
            'foto_profil'    => 'nullable|image|max:2048',
            'biografi'       => 'nullable|string'
        ]);

        if ($request->hasFile('foto_profil')) {
            $validated['foto_profil'] = $request->file('foto_profil')->store('sutradara', 'public');
        }

        Sutradara::create($validated);

        return redirect()
            ->route('admin.sutradara.index')
            ->with('success', 'Sutradara berhasil ditambahkan');
    }

    public function edit($id)
    {
        $sutradara = Sutradara::findOrFail($id);
        return view('livewire.admin.sutradara-edit', compact('sutradara'));
    }

    public function update(Request $request, $id)
    {
        $sutradara = Sutradara::findOrFail($id);

        $validated = $request->validate([
            'nama_sutradara' => 'required|string|max:100',
            'foto_profil'    => 'nullable|image|max:2048',
            'biografi'       => 'nullable|string'
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($sutradara->foto_profil && Storage::disk('public')->exists($sutradara->foto_profil)) {
                Storage::disk('public')->delete($sutradara->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')->store('sutradara', 'public');
        }

        $sutradara->update($validated);

        return redirect()
            ->route('admin.sutradara.index')
            ->with('success', 'Sutradara berhasil diperbarui');
    }

    public function destroy($id)
    {
        $sutradara = Sutradara::findOrFail($id);

        // Cek apakah sutradara dipakai di film manapun
        if ($sutradara->films()->exists()) {
            return redirect()
                ->route('admin.sutradara.index')
                ->with('error', 'Sutradara tidak bisa dihapus karena masih digunakan oleh film.');
        }

        // Hapus foto profil jika ada
        if ($sutradara->foto_profil && Storage::disk('public')->exists($sutradara->foto_profil)) {
            Storage::disk('public')->delete($sutradara->foto_profil);
        }

        // Hapus data sutradara
        $sutradara->delete();

        return redirect()
            ->route('admin.sutradara.index')
            ->with('message', 'Sutradara berhasil dihapus');
    }
}
