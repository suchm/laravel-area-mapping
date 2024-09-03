<?php

namespace App\Livewire;

use App\Livewire\Forms\AreasForm;
use App\Models\Area;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AreasEdit extends Component
{
    public AreasForm $form;
    public Collection $categories;

    public function mount(Area $area): void
    {
//        dd(Area $area);
        $this->form->setArea($area);
        $this->categories = Category::pluck('name', 'id');
    }

    public function submit()
    {
        $this->validate();

        $this->form->update();

        $this->redirect('/areas');
    }

    public function render()
    {
        return view('livewire.areas.edit')->extends('layouts.app');
    }
}
