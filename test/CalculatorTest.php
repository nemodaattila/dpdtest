<?php

namespace test;

use model\Coords;
use model\RectangleArea;
use model\RectangleAreaException;
use model\RequestParameters;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use rest\CalculatorRest;

class CalculatorPrivateTest extends TestCase
{
    private CalculatorRest $cr;
    private ReflectionClass $crref;
    private static RectangleArea $areaModel;

    protected function setUp(): void
    {
        $this->cr = new CalculatorRest();
        $this->crref = new ReflectionClass(CalculatorRest::class);
        $fnc = $this->crref->getMethod('createRectangleArea');
        $fnc->setAccessible(true);
        $fnc->invokeArgs($this->cr, [[[1,2],[3,4]]]);
    }

    public function testRectangleAreaModelCreated()
    {
        $fnc = $this->crref->getMethod('getCalculatedAreaModel');
        $fnc->setAccessible(true);
        self::$areaModel=$fnc->invoke($this->cr);
        $this->assertInstanceOf(\model\RectangleArea::class,self::$areaModel);
    }


    public function testFenceResultContainsPrice()
    {
        $fnc = $this->crref->getMethod('calculateFence');
        $fnc->setAccessible(true);
        $fnc->invokeArgs($this->cr, [self::$areaModel]);

        $fnc = $this->crref->getMethod('compileSuccessResponseData');
        $fnc->setAccessible(true);
        $result = $fnc->invoke($this->cr);
        $this->assertArrayHasKey('price', $result);
        $this->assertNotNull($result['price']);
        $this->assertGreaterThan(0, $result['price']);
    }

    public function testNotNumericParameterThrowsRectangleException()
    {
        $fnc = $this->crref->getMethod('checkParametersAreNumeric');
        $fnc->setAccessible(true);
        $this->expectException(RectangleAreaException::class);
        $this->expectExceptionMessage('a coordinate parameter is in bad format');
        $fnc->invokeArgs($this->cr, [['a',1.1]]);
    }

    public function testIntParametersAreAcceptable()
    {
        $fnc = $this->crref->getMethod('checkParametersAreNumeric');
        $fnc->setAccessible(true);
        $result = $fnc->invokeArgs($this->cr, [[1,2]]);
        $this->assertIsFloat($result[0]);
        $this->assertIsFloat($result[1]);
    }

}
