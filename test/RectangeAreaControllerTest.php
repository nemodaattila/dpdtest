<?php

namespace test;

use controller\RectangleArea;
use model\Coords;
use model\RectangleAreaException;
use PHPUnit\Framework\TestCase;

class RectangeAreaControllerTest extends TestCase
{
    public function testThrowsExceptionIfModelISNotSet()
    {
        $controller = new RectangleArea();
        $this->expectException(RectangleAreaException::class);
        $this->expectExceptionMessage('RectangleArea Model not set');
        $controller->calcAreaData();
    }

    public function testRectangleDataIsCorrect()
    {
        $controller = new RectangleArea();
        $controller->addCoordinates(new Coords(1.2345, 2.23456),
            new Coords(4.4567, 6.67891));
        $controller->calcAreaData();
        $model=$controller->getAreaModel();
        $this->assertInstanceOf('\model\RectangleArea', $model);
        $this->assertEquals('494745.042', $model->getWidth());

    }
}
