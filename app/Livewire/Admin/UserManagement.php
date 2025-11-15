<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $roleFilter = '';

    protected $queryString = ['search', 'roleFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if ($user && $user->id !== auth()->id()) {
            // Hapus foto profil jika ada dan bukan default
            if ($user->foto_profil && $user->foto_profil !== 'default-avatar.png') {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $user->delete();

            // Dispatch event untuk SweetAlert
            $this->dispatch('user-deleted', ['message' => 'User berhasil dihapus']);
        } else {
            // Dispatch event untuk error
            $this->dispatch('user-delete-failed', ['message' => 'Tidak dapat menghapus user ini']);
        }
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user-management', compact('users'))
            ->layout('admin.layouts.app');
    }
}
