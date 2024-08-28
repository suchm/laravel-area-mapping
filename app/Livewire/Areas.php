<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithPagination;

class Areas extends Component
{
    use WithPagination;

    //use WithoutUrlPagination;

    public Collection $categories;
    #[Session]
    public string $searchQuery = '';
    public int $searchCategory = 0;

    public function mount(): void
    {
        $this->categories = Category::pluck('name', 'id');
    }

    // You need to reset the page when using pagination for it to work
    public function updating($key): void
    {
        if ($key === 'searchQuery' || $key === 'searchCategory') {
            $this->resetPage();
        }
    }

    public function deleteArea(int $areaId): void
    {
        Area::where('id', $areaId)->delete();
    }

    public function render(): View
    {
        $areas = Area::with('category')
            ->when($this->searchQuery !== '',
                fn(Builder $query) => $query->where('name', 'ilike', '%'.$this->searchQuery.'%')
            ->orWhere('description', 'ilike', '%'.$this->searchQuery.'%'))
            ->when($this->searchCategory > 0,
                fn(Builder $query) => $query->where('category_id', $this->searchCategory))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.areas.index', compact('areas'))
            ->layout('layouts.app');
    }
}
