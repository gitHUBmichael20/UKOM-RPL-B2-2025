<?php

namespace App\Livewire\Admin;

use App\Models\Studio;
use Livewire\Component;
use Livewire\WithPagination;

class StudioManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $deleteId;
    public $viewLayoutId;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('show-delete-modal');
    }

    public function viewLayout($id)
    {
        $this->viewLayoutId = $id;
        $this->dispatch('show-layout-modal');
    }

    public function delete()
    {
        $studio = Studio::find($this->deleteId);
        
        if ($studio) {
            $studio->delete();
            session()->flash('success', 'Studio berhasil dihapus!');
        }

        $this->deleteId = null;
        $this->dispatch('hide-delete-modal');
    }

    public function render()
    {
        $studio = Studio::query()
            ->when($this->search, function ($query) {
                $query->where('nama_studio', 'like', '%' . $this->search . '%')
                      ->orWhere('tipe_studio', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $layoutStudio = null;
        if ($this->viewLayoutId) {
            $layoutStudio = Studio::with('kursi')->find($this->viewLayoutId);
        }

        return view('livewire.admin.studio-management', [
            'studio' => $studio,
            'layoutStudio' => $layoutStudio
        ])->layout('admin.layouts.app');
    }
}