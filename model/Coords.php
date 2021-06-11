<?php

namespace model;

use JetBrains\PhpStorm\Pure;

/**
 * Class Coords class model for geographic coordinates
 * @package model
 */
class Coords
{
    /**
     * @var float the coordinate for latitude
     */
    private float $latitude;

    /**
     * @var float the coordinate for longitude
     */
    private float $longitude;

    /**
     * Coords constructor.
     * @param float $latitude latitude coordinate
     * @param float $longitude longitude coordinate
     */
    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->validateCoords();
    }


    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * returns the coordinates as one string
     * @return string coordinates in a string format
     */
    public function getAsString(): string
    {
        return sprintf("%001.5f", $this->getLatitude()) . ' ' . sprintf("%001.5f", $this->getLongitude());
    }

    private function validateCoords()
    {
        if ($this->getLatitude()<-90 || $this->getLatitude()>90 || $this->longitude >180 || $this->longitude < -180)
        {
            throw new RectangleAreaException('on of the coordinates is out of bounds');
        }
    }

}
