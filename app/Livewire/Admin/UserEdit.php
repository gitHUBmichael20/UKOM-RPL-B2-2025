<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserEdit extends Component
{
    use WithFileUploads;

    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $phone;
    public $role;
    public $foto_profil;
    public $current_foto;

    public function mount($id)
    {
        $user = User::findOrFail($id);
        
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role = $user->role;
        $this->current_foto = $user->foto_profil;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $this->userId,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,kasir,pelanggan',
            'foto_profil' => 'nullable|image|max:2048',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'role.required' => 'Role wajib dipilih.',
        'foto_profil.image' => 'File harus berupa gambar.',
        'foto_profil.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function update()
    {
        $this->validate();

        $user = User::findOrFail($this->userId);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->foto_profil) {
            // Hapus foto lama jika ada dan bukan default
            if ($user->foto_profil && $user->foto_profil !== 'default_profile.jpg') {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $this->foto_profil->store('profile-photos', 'public');
        }

        $user->update($data);

        session()->flash('success', 'User berhasil diupdate.');
        return redirect()->route('admin.users.index');
    }

    public function deletePhoto()
    {
        $user = User::findOrFail($this->userId);
        
        if ($user->foto_profil && $user->foto_profil !== 'default_profile.png') {
            Storage::disk('public')->delete($user->foto_profil);
            $user->update(['foto_profil' => null]);
            $this->current_foto = null;
            session()->flash('message', 'Foto profil berhasil dihapus.');
        }
    }

    public function render()
    {
        return view('livewire.admin.user-edit')
            ->layout('admin.layouts.app');
    }
}