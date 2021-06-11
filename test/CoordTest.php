<?php

namespace test;

use model\Coords;
use model\RectangleAreaException;
use PHPUnit\Framework\TestCase;

class CoordTest extends TestCase
{
    public function testGivenCoordsAreFloat()
    {
        $coord = new Coords(12.1, 3.0);
        $this->assertIsFloat($coord->getLatitude());
        $this->assertIsFloat($coord->getLongitude());
    }

    public function testCoordsAsString()
    {
        $coord = new Coords(12.1, 3.0);
        $this->assertEquals('12.10000 3.00000', $coord->getAsString());
    }

    public function testCoordThrowsAnExceptionOnLatitudeOutOFBounds()
    {
        $this->expectException(RectangleAreaException::class);
        new Coords(90.1, 3.0);
    }

    public function testCoordThrowsAnExceptionLongitudeOutOFBounds()
    {
        $this->expectException(RectangleAreaException::class);
        $this->expectExceptionMessage('on of the coordinates is out of bounds');
        new Coords(10.1, -190.0);
    }
}
