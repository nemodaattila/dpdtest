<?php

namespace model;


/**
 * Class RequestParameters contains the parameters of a http request
 * @package core\backend\model
 */
class RequestParameters
{
    /**
     * @var array xmlhttprequest url parameters
     */
    private array $urlParameters;
    /**
     * @var array http request body parameters
     */
    private array $requestData;

    public function addUrlParameter(string $urlParameter): void
    {
        $this->urlParameters[] = $urlParameter;
    }

    public function setRequestData(array $requestData): void
    {
        $this->requestData = $requestData;
    }

    public function getRequestData(): array
    {
        return $this->requestData;
    }

    /**
     * empties parameters
     */
    public function reset()
    {
        $this->urlParameters = [];
        $this->requestData = [];
    }
}
