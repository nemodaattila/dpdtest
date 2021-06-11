<?php

namespace rest;

use Error;
use Exception;
use interfaces\IRestInterface;

/**
 * Class RequestHandler analyzes the http request, searches for Rest object and calls the
 * appropriate function
 * @package rest
 */
class RequestHandler
{
    /**
     * @var array target to be loaded based on http request url
     */
    private array $target;

    /**
     * default target if $target is empty
     * @var array|string[]
     */
    private array $defaultTarget = ["index"];

    /**
     * @var IRestInterface rest Object to be called based on the request url
     */
    private IRestInterface $rest;

    public function __construct()
    {
        $this->analyzeRequest();
        $this->checkRestExists();
        $this->initRouter();
    }

    /**
     * analyzes the request url, and gets the target
     */
    private function analyzeRequest()
    {
        $separator = $_SERVER['REQUEST_URI'][0];
        $urlStripper = str_replace($_SERVER['CONTEXT_DOCUMENT_ROOT'], "", ROOT);
        $request = explode($separator, (str_replace($urlStripper, "", $_SERVER['REQUEST_URI'])));
        foreach ($request as $value) {
            if ($value !== '') {
                $this->target[] = $value;
            }
        }
        if (empty($this->target)) $this->target = $this->defaultTarget;
    }

    /**
     * checks that the rest Object exists based on $target, if not error response
     */
    private function checkRestExists()
    {
        try {
            $rest = '\rest\\' . ucfirst($this->target[0]) . 'Rest';
            $this->rest = new $rest();
        } catch (Error $e) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Error');
            header("Content-Type: application/json");
            echo json_encode(array("errorMessage" => $e->getMessage()));
            die();
        }
    }

    /**
     * initiates the router and calls the appropriate function, based on $target
     * send http response on missing router and fatal error
     */
    private function initRouter()
    {
        try {
            $routing = Routing::getInstance();
            $routing->addRoutes($this->rest);
            $routing->setCors("*", "origin, content-type, accept, authorization");
            $routing = $routing->processRoutingRequest($this->target);
            if (!$routing) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 400 ERROR');
                header("Content-Type: application/json");
                echo json_encode(array("errorCode" => 'ROUTER_NOT_FOUND'));
                die();
            }
        } catch (Exception $e) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 FATAL ERROR');
            die(json_encode($e->getMessage()));
        }
    }

}
