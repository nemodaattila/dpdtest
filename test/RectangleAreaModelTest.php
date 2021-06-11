<?php

namespace test;

use model\RectangleArea;
use model\Coords;
use PHPUnit\Framework\TestCase;

class RectangleAreaModelTest extends TestCase
{
    private RectangleArea $rectArea;

    protected function setUp(): void
    {
        $a = new Coords(1.2345, 2.23456);
        $b = new Coords(4.4567, 6.67891);
        $this->rectArea = new RectangleArea($a, $b);
        $this->rectArea->calcCDPoints();
        $this->rectArea->calcSides();
        $this->rectArea->calcPerimeter();
        $this->rectArea->calcArea();
    }

    
    public function testSidesAreCorrect()
    {
        $this->assertEquals(494745.042, $this->rectArea->getWidth());
        $this->assertEquals(358695.304, $this->rectArea->getHeight());
    }

    public function testParametersAreCorrect()
    {
        $params = $this->rectArea->getAllData();
        $this->assertIsString($params['cpoint']);
        $this->assertIsString($params['dpoint']);
        $this->assertIsFloat($params['perimeter']);
        $this->assertIsFloat($params['area']);
        $this->assertEquals('1.23450 6.67891', $params['cpoint']);
        $this->assertEquals('4.45670 2.23456', $params['dpoint']);
        $this->assertEquals('1706880.692', $params['perimeter']);
        $this->assertEquals('177462723242.68277', $params['area']);

    }


}
