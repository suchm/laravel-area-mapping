<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidGeoJsonFormat implements Rule
{
    public function passes($attribute, $value)
    {
        // Try to decode the JSON
        $geojson = json_decode($value, true);

        // Check if JSON decoding was successful and the basic structure is valid
        if (json_last_error() !== JSON_ERROR_NONE || !isset($geojson['type']) || !isset($geojson['features'])) {
            return false;
        }

        // Ensure the GeoJSON type is "FeatureCollection"
        if ($geojson['type'] !== 'FeatureCollection') {
            return false;
        }

        // Ensure "features" is an array
        if (!is_array($geojson['features'])) {
            return false;
        }

        // Validate each feature
        foreach ($geojson['features'] as $feature) {
            if (!isset($feature['type']) || $feature['type'] !== 'Feature') {
                return false;
            }

            if (!isset($feature['geometry']) || !is_array($feature['geometry'])) {
                return false;
            }

            $geometryType = $feature['geometry']['type'];
            $coordinates = $feature['geometry']['coordinates'];

            if (!in_array($geometryType, ['Point', 'MultiPoint', 'LineString', 'MultiLineString', 'Polygon', 'MultiPolygon', 'GeometryCollection'])) {
                return false;
            }

            // Validate coordinates based on geometry type
            if (!$this->validateCoordinates($geometryType, $coordinates)) {
                return false;
            }

            if (isset($feature['properties']) && !is_array($feature['properties'])) {
                return false;
            }

            if (isset($feature['id']) && !is_string($feature['id']) && !is_numeric($feature['id'])) {
                return false;
            }
        }

        return true;
    }

    protected function validateCoordinates($geometryType, $coordinates)
    {
        switch ($geometryType) {
            case 'Point':
                return $this->validatePoint($coordinates);

            case 'MultiPoint':
            case 'LineString':
                return $this->validateLineString($coordinates);

            case 'MultiLineString':
            case 'Polygon':
                return $this->validatePolygon($coordinates);

            case 'MultiPolygon':
                return $this->validateMultiPolygon($coordinates);

            default:
                return false;
        }
    }

    protected function validatePoint($coordinates)
    {
        return is_array($coordinates) && count($coordinates) === 2;
    }

    protected function validateLineString($coordinates)
    {
        return is_array($coordinates) && count($coordinates) >= 2 && $this->allPointsValid($coordinates);
    }

    protected function validatePolygon($coordinates)
    {
        if (!is_array($coordinates)) {
            return false;
        }

        foreach ($coordinates as $ring) {
            if (!$this->validateLinearRing($ring)) {
                return false;
            }
        }

        return true;
    }

    protected function validateMultiPolygon($coordinates)
    {
        if (!is_array($coordinates)) {
            return false;
        }

        foreach ($coordinates as $polygon) {
            if (!$this->validatePolygon($polygon)) {
                return false;
            }
        }

        return true;
    }

    protected function validateLinearRing($ring)
    {
        // A linear ring must have at least 4 positions, and the first and last positions must be the same
        return is_array($ring) && count($ring) >= 4 && $ring[0] === $ring[count($ring) - 1] && $this->allPointsValid($ring);
    }

    protected function allPointsValid($points)
    {
        foreach ($points as $point) {
            if (!$this->validatePoint($point)) {
                return false;
            }
        }
        return true;
    }

    public function message()
    {
        return 'The Geojson data is not a valid format.';
    }
}
