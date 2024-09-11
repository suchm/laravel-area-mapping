<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Area>
 */
class AreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $geojson = '{
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
        }';

        $geojsonDecoded = json_decode($geojson);
        $geomEncoded = json_encode($geojsonDecoded->features[0]->geometry);
        $geometry = DB::raw("ST_GeomFromGeoJSON('{$geomEncoded}')");

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'name' => 'Area 1',
            'description' => fake()->sentence(10),
            'geojson' => $geojson,
            'geometry' => $geometry,
            'in_breaches' => 1,
            'valid_from' => now(),
            'valid_to' => null
        ];
    }
}
