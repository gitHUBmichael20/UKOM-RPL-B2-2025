<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;

class UserCreate extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $phone;
    public $role = 'pelanggan';
    public $foto_profil;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,kasir,pelanggan',
            'foto_profil' => 'nullable|image|max:2048', // Max 2MB
        ];
    }

    protected $messages = [
        'name.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'role.required' => 'Role wajib dipilih.',
        'foto_profil.image' => 'File harus berupa gambar.',
        'foto_profil.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function save()
    {
        $this->validate();

        $fotoPath = null;
        if ($this->foto_profil) {
            $fotoPath = $this->foto_profil->store('profile-photos', 'public');
        }
        

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'role' => $this->role,
            'foto_profil' => $fotoPath,
        ]);

        session()->flash('message', 'User berhasil ditambahkan.');
        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.user-create')
            ->layout('admin.layouts.app');
    }
}