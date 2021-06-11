<?php

namespace interfaces;

/**
 * Interface IRestInterface InterFace for handling Http requests
 * @package core\backend\interfaces
 */
interface IRestInterface
{
    /**
     * @return array return the defined routes
     */
    public function getRoutes(): array;
}
