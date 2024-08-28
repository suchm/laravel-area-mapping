<?php

namespace App\Livewire;

use App\Livewire\Forms\AreasForm;
use App\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Component;
use Nette\Schema\ValidationException;

class AreasCreate extends Component
{
    public AreasForm $form;
    public Collection $categories;

    public function mount()
    {
        $this->categories = Category::pluck('name', 'id');
    }

    public function submit()
    {
        $this->form->save();

        $this->redirect('/areas');
    }

    public function render()
    {
        return view('livewire.areas.create');
    }
}
