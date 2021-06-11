<?php

namespace controller;

use Error;
use model\Coords;
use model\RectangleAreaException;

/**
 * Class RectangleArea controller for RectangleArea - calculates the coordinates, area, perimeter,
 * and the price of the surrounding wire-fence
 * @package controller
 */
class RectangleArea
{
    /**
     * @var \model\RectangleArea model for RectangleArea
     */
    private \model\RectangleArea $area;

    /**
     * setting the two, diagonally opposite points(coordinates) of a rectangle
     * (A and C point clockwise, where A is the upper left point)
     * @param Coords $coordA coordinates of the point A
     * @param Coords $coordC coordinates of the point C
     */
    public function addCoordinates(Coords $coordA, Coords $coordC)
    {
        $this->area = new  \model\RectangleArea($coordA, $coordC);
    }

    /**
     * calls data calculating functions of the RectangleArea
     */
    public function calcAreaData()
    {
        if (!isset ($this->area)) {
            throw new RectangleAreaException('RectangleArea Model not set');
        }
        $this->area->calcCDPoints();
        $this->area->calcSides();
        $this->area->calcPerimeter();
        $this->area->calcArea();
    }

    /**
     * returns the model of RectangleArea
     * @return \model\RectangleArea the model of RectangleArea
     */
    public function getAreaModel(): \model\RectangleArea
    {
        return $this->area;
    }

}
