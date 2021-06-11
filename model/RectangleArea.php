<?php

namespace model;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 * Class RectangleArea model class for a Rectangular Area - corner-points(coordinates), width, length, area, perimeter
 * points are clockwise, beginning from point A in the upper left corner
 * @package model
 */
class RectangleArea
{
    /**
     * @var Coords coordinates of the point A of the rectangle
     */
    private Coords $pointA;

    /**
     * @var Coords coordinates of the point B of the rectangle
     */
    private Coords $pointB;

    /**
     * @var Coords coordinates of the point C of the rectangle
     */
    private Coords $pointC;

    /**
     * @var Coords coordinates of the point D of the rectangle
     */
    private Coords $pointD;

    /**
     * @var float the length of the horizontal sides of the rectangle
     */
    private float $width;

    /**
     * @var float the length of the vertical sides of the rectangle
     */
    private float $height;

    /**
     * @var float the area of the rectangle
     */
    private float $area;

    /**
     * @var float the perimeter of the rectangle
     */
    private float $perimeter;

    public function getWidth(): float
    {
        return $this->width;
    }


    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * RectangleArea constructor.
     * @param Coords $pointA
     * @param Coords $pointC
     */
    public function __construct(Coords $pointA, Coords $pointC)
    {
        $this->pointA = $pointA;
        $this->pointC = $pointC;
    }

    public function calcCDPoints()
    {
        $this->pointB = new Coords($this->pointA->getLatitude(), $this->pointC->getLongitude());
        $this->pointD = new Coords($this->pointC->getLatitude(), $this->pointA->getLongitude());
    }

    public function calcSides()
    {
        $this->width = abs($this->pointC->getLongitude() - $this->pointD->getLongitude()) * ONE_DECIMAL_DEGREE_IN_METER;
        $this->height = abs($this->pointA->getLatitude() - $this->pointD->getLatitude()) * ONE_DECIMAL_DEGREE_IN_METER;
        if ($this->width <= 6 || $this->height <=6)
        {
            throw new RectangleAreaException("Rectangle Side is to small");
        }
    }

    public function calcPerimeter()
    {
        $this->perimeter = 2 * ($this->width + $this->height);
    }

    public function calcArea()
    {
        $this->area = $this->width * $this->height;
    }

    /**
     * return all data about the rectangular area (except point A and C)
     * @return array
     */
    public function getAllData(): array
    {
        return ["cpoint" => $this->pointB->getAsString(),
            "dpoint" => $this->pointD->getAsString(),
            "perimeter" => $this->perimeter,
            "area" => $this->area
        ];
    }

}
