<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        return view('areas.index');
    }

    public function create()
    {
        return view('areas.create');
    }

    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }
}
