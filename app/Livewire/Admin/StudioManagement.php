<?php

namespace App\Livewire\Admin;

use App\Models\Studio;
use Livewire\Component;
use Livewire\WithPagination;

class StudioManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $viewLayoutId;
    public $showLayoutModal = false;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewLayout($id)
    {
        $this->viewLayoutId = $id;
        $this->showLayoutModal = true;
    }

    public function closeLayoutModal()
    {
        $this->showLayoutModal = false;
        $this->viewLayoutId = null;
    }

    public function delete($id)
    {
        $studio = Studio::find($id);

        if ($studio) {
            $studio->delete();
            $this->dispatch('studio-deleted');
        }
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
