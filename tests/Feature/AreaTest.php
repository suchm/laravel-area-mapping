<?php

use App\Livewire\Areas;
use App\Livewire\AreasCreate;
use App\Livewire\AreasEdit;
use App\Models\Area;
use App\Models\Category;
use App\Models\User;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->category = Category::factory()->create();
    $this->area = Area::factory()->create([
        'name' => 'Area 1',
        'category_id' => $this->category->id,
    ]);

    $this->areaArray = [
        'form.name' => 'Area 2',
        'form.geojson' => '{
        "type": "FeatureCollection",
          "features": [
            {
              "type": "Feature",
              "properties": {},
              "geometry": {
                "coordinates": [
                    [
                        [
                            -13.244147157190639,
                            26.187290969899664
                        ],
                        [
                            25.88628762541805,
                            -29.197324414715723
                        ],
                        [
                            46.65551839464882,
                            -6.923076923076934
                        ],
                        [
                            23.47826086956522,
                            31.003344481605346
                        ],
                        [
                            -13.244147157190639,
                            26.187290969899664
                        ]
                    ]
                ],
                "type": "Polygon"
              }
            }
          ]
        }',
        'form.in_breaches' => 1,
        'form.category' => $this->category->id,
        'form.valid_from' => now(),
    ];
});

test('areas can be accessed by authenticated user', function () {

    actingAs($this->user)
        ->get('/areas')
        ->assertStatus(200);
});

test('areas contain non empty table', function () {

    Livewire::test(Areas::class)
        ->assertSee('Area 1');
});

test('user can create an area', function () {

    actingAs($this->user);

    Livewire::test(AreasCreate::class)
        ->fill($this->areaArray)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(route('areas'));
});

test('it rejects invalid geojson during area creation', function () {

    actingAs($this->user);

    $area = [
        'form.user_id' => $this->user->id,
        'form.name' => 'Invalid Area',
        'form.geojson' => '{"invalid":"data"}',
        'form.category' => $this->category->id,
        'form.valid_from' => now()
    ];

    Livewire::test(AreasCreate::class)
        ->fill($area)
        ->call('submit')
        ->assertHasErrors();
});

test('user can update an area', function () {

    actingAs($this->user);

    Livewire::test(AreasEdit::class)
        ->fill($this->areaArray)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(route('areas'));
});

test('an area can be associated with a category', function () {
    $category = Category::factory()->create();
    $area = Area::factory()->create(['category_id' => $category->id]);

    expect($area->category->id)->toBe($category->id);
});
