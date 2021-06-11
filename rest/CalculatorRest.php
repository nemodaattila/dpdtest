<?php

namespace rest;

use controller\Fence;
use controller\RectangleArea;
use interfaces\IRestInterface;
use model\Coords;
use model\RectangleAreaException;
use model\RequestParameters;

/**
 * Class CalculatorRest http request processor
 * @package rest
 */
class CalculatorRest implements IRestInterface
{
    /**
     * @var array|array[] possible routes
     */
    protected array $routes = [
        ['POST', 'calculator', 'calcPrice', false, true, true],
    ];

    private RectangleArea $area;

    private Fence $fence;

    /**
     * returns all defined routes
     * @return array possible routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * calls functions to calculate the price of a Fence surrounding a Rectangular area
     * sends a http response as result
     * @param RequestParameters $parameters data from http request
     */
    public function calcPrice(RequestParameters $parameters)
    {
        try {
            $coords = $parameters->getRequestData();
            $this->createRectangleArea($coords);
            $this->calculateFence();
            $this->sendResponse($this->compileSuccessResponseData());

        } catch (RectangleAreaException $e) {
            $data['success'] = false;
            $data['errorMessage'] = $e->getMessage();
            $this->sendResponse($data);
        }
    }

    /**
     * crates a RectangleArea and calculates it's data
     * @param array $coords two diagonally opposite coordinates
     * @throws RectangleAreaException if the parameters are not numeric
     */
    private function createRectangleArea(array $coords)
    {
        $coords[0] = $this->checkParametersAreNumeric($coords[0]);
        $coords[1] = $this->checkParametersAreNumeric($coords[1]);
        $a = new Coords($coords[0][0], $coords[0][1]);
        $b = new Coords($coords[1][0], $coords[1][1]);
        $this->area = new RectangleArea();
        $this->area->addCoordinates($a, $b);
        $this->area->calcAreaData();

    }

    /**
     * checks coordinate parameters are numeric
     * @param $params [latitude, longitude]
     * @return float[] parameters in folat
     * @throws RectangleAreaException if one of the parameters are not numeric
     */
    private function checkParametersAreNumeric($params): array
    {
        [$a, $b] = $params;
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new RectangleAreaException('a coordinate parameter is in bad format');
        }
        return [(float)$a, (float)$b];
    }

    /**
     * returns the area Model
     * @return \model\RectangleArea
     */
    private function getCalculatedAreaModel(): \model\RectangleArea
    {
        return $this->area->getAreaModel();
    }

    /**
     * creates a Fence controller and calculates the Price of the surrouning Fence
     */
    private function calculateFence()
    {
        $this->fence = new Fence();
        $this->fence->calculateFenceItems($this->getCalculatedAreaModel());
        $this->fence->calculateFencePrice();
    }

    /**
     * returns the Area's B AND D coordinates, the permieter (m), the area (m^2), the price of the fence
     * and a 'success' bool
     * @return array collected data
     */
    private function compileSuccessResponseData(): array
    {
        $data = $this->getCalculatedAreaModel()->getAllData();
        $data['price'] = $this->fence->getPrice();
        $data['success'] = true;
        return $data;
    }

    /**
     * compiles and sends http response with data
     * @param array $data response data
     */
    private function sendResponse(array $data)
    {
        $resp = new CustomHttpResponse([$_SERVER['SERVER_PROTOCOL'] . ' 200 OK', "Content-Type: application/json"], $data);
        $resp->send();
    }

}
