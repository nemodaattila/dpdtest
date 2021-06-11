<?php

namespace test;

use model\Coords;
use model\RectangleArea;
use model\RectangleAreaException;
use PHPUnit\Framework\TestCase;

class RectangleAreaModelExceptionTest extends TestCase
{
    public function testCalcSidesThrowsExceptionWhenSideIsSmall()
    {
        $a = new Coords(0.0001, 0.0001);
        $b = new Coords(0.0001, 0.0001);
        $rectArea = new RectangleArea($a, $b);
        $rectArea->calcCDPoints();
        $this->expectException(RectangleAreaException::class);
        $this->expectExceptionMessage('Rectangle Side is to small');
        $rectArea->calcSides();
    }
}
