<?php

namespace rest;

use interfaces\IRestInterface;

/**
 * Class IndexRest http request processor
 * @package rest
 */
class IndexRest implements IRestInterface
{
    /**
     * @var array|array[] possible routes
     */
    protected array $routes = [
        ['GET', 'index', 'displayCalc', false, false, true],
    ];

    /**
     * returns all defined routes
     * @return array possible routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * displays the rectangle calculator html page
     */
    public function displayCalc()
    {
        require_once ROOT . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "index.html";
    }
}
