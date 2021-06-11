<?php

namespace test;

use controller\RectangleArea;
use model\Coords;
use PHPUnit\Framework\TestCase;

class FenceControllerTest extends TestCase
{
    public function testPriceEqualsExpected()
    {
        $controller = new RectangleArea();
        $controller->addCoordinates(new Coords(1.2345, 2.23456),
            new Coords(4.4567, 6.67891));
        $controller->calcAreaData();
        $model = $controller->getAreaModel();
        $fc = new \controller\Fence();
        $fc->calculateFenceItems($model);
        $fc->calculateFencePrice();
        $this->assertEquals(116381650,$fc->getPrice());
    }}
