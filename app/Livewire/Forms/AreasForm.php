<?php

namespace App\Livewire\Forms;

use App\Models\Area;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportEvents\HandlesEvents;
use Livewire\Form;
use App\Rules\ValidGeoJsonFormat;

class AreasForm extends Form
{
    public ?Area $area;
    public $name;
    public ?string $description = null;
    public $category;
    public $valid_from;
    public ?string $valid_to = null;
    public $in_breaches = false;
    public $geojson;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:64',
            'description' => 'nullable|string|max:255',
            'category' => 'required|integer',
            'valid_from' => 'required|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
            'in_breaches' => 'boolean',
            'geojson' => ['required', 'json', new ValidGeoJsonFormat()],
        ];
    }

    public function setArea(Area $area): void
    {
        $this->area = $area;
        $this->name = $area->name;
        $this->description = $area->description;
        $this->category = $area->category_id;
        $this->valid_from = $area->valid_from ? $area->valid_from->format('Y-m-d') : null;
        $this->valid_to = $area->valid_to ? $area->valid_to->format('Y-m-d') : null;
        $this->in_breaches = $area->in_breaches;
        $this->geojson = $area->geojson;
    }

    public function save(): void
    {
        $this->validate();
        $model = $this->getModel($this->geojson);
        Area::create($model);
    }

    public function update(): void
    {
        $this->validate();
        $model = $this->getModel($this->geojson);
        $this->area->update($model);
    }

    protected function getGeometry($geojson)
    {
        $geojsonDecoded = json_decode($geojson);
        $geomEncoded = json_encode($geojsonDecoded->features[0]->geometry);
        return DB::raw("ST_GeomFromGeoJSON('{$geomEncoded}')");
    }

    protected function getModel($geojson)
    {
        $geometry = $this->getGeometry($geojson);

        return [
            'user_id' => auth()->id(),
            'category_id' => $this->category,
            'name' => $this->name,
            'description' => $this->description,
            'geojson' => $this->geojson,
            'geometry' => $geometry,
            'valid_from' => $this->valid_from ?: null,
            'valid_to' => $this->valid_to ?: null,
            'in_breaches' => $this->in_breaches,
        ];
    }
}
