<?php

namespace rest;

use Error;
use Exception;
use helper\VariableHelper;
use interfaces\IRestInterface;
use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;
use model\RequestParameters;
use stdClass;

/**
 * Class Routing checks routes, and analyses request and calls appropriate function
 * SINGLETON class
 * @package backend
 */
final class Routing
{
    /**
     * @var Routing|null Singleton instance of Routing
     */
    private static ?Routing $instance = null;

    /**
     * @var bool is cors Enabled
     */
    private bool $cors;

    /**
     * @var IRestInterface a request processor
     */
    private IRestInterface $rest;

    /**
     * @var array five routes
     */
    private array $routes = array();

    /**
     * @var string|null CORS allowed origin
     */
    private ?string $allowedOrigin;

    /**
     * @var string|null CORS allowed headers
     */
    private ?string $allowedHeaders;

    /**
     * @var RequestParameters http request parameters
     */
    private RequestParameters $requestParameters;

    public static function getInstance(): ?Routing
    {
        if (self::$instance == null) {
            self::$instance = new Routing();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->cors = false;
        $this->requestParameters = new RequestParameters();
    }

    /**
     * retrieves the routes of the rest processor
     * @param IRestInterface $rest rest processor (first parameter of the request uri)
     */
    public function addRoutes(IRestInterface $rest)
    {
        $this->rest = $rest;
        $routes = $this->rest->getRoutes();
        foreach ($routes as $value) {
            $this->addRoute(...$value);
        }

    }

    /**
     * adds a route to the router
     * @param string $httpMethod request type - get/post/put/delete
     * @param string $url - request url: user/$1
     * @param string $task - function to be called
     * @param bool $authRequired - authentication is enabled - default false
     * @param bool $isJson - request has json data/ header - default true
     */
    private function addRoute(string $httpMethod, string $url, string $task, bool $authRequired = false,
                              bool $isJson = true, bool $responseIsJson = true)
    {
        array_push($this->routes, array(
            "http_method" => $httpMethod,
            "url" => $url,
            "task" => $task,
            "auth_required" => $authRequired,
            'is_json' => $isJson,
            'response_is_json' => $responseIsJson
        ));
    }

    /**
     * set CORS policy
     * @param string $allowedOrigin allowed sites
     * @param string $allowedHeader allowed headers
     */
    public function setCors(string $allowedOrigin, string $allowedHeader)
    {
        $this->cors = true;
        $this->allowedOrigin = $allowedOrigin;
        $this->allowedHeaders = $allowedHeader;
    }

    /**
     * check if route exists with given parameters
     * saves url parameters
     * @param $httpMethod - a http method
     * @param $url - url
     * @return bool url exists
     * @example GET /user/admin/ => /user/$1/
     */
    private function identifyHeader(string $httpMethod, string $url, array $path): bool
    {
        if ($_SERVER['REQUEST_METHOD'] !== strtoupper($httpMethod) && ($this->cors === false || $_SERVER['REQUEST_METHOD'] !== 'OPTIONS')) {
            return false;
        }
        $url = explode('/', $url);
        if (count($path) !== count($url)) {
            return false;
        }
        $length = count($path);
        for ($i = 0; $i < $length; $i++) {
            if ($path[$i] !== $url[$i]) {
                if (preg_match('/\$([0-9]+?)/', $url[$i]) !== 1) {
                    $this->requestParameters->reset();
                    return false;
                }
                $this->requestParameters->addUrlParameter(filter_var($path[$i], FILTER_SANITIZE_STRING));
            }
        }
        return true;
    }

    /**
     * searches for appropriate route , collects request parameters, and calls appropriate function, based on request
     * @return bool false - on error, or on non-existing route, otherwise calls appropriate function
     */
    public function processRoutingRequest(array $urlTarget): bool
    {
        $supportedHeaders = array();
        foreach ($this->routes as $route) {
            if ($this->identifyHeader($route['http_method'], $route['url'], $urlTarget)) {

                if ($this->cors == true && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                    array_push($supportedHeaders, strtoupper($route['http_method']));
                } else {
                    if ($route['is_json'] && isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json')) {
                        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
                            $putVars = [];
                            parse_str(file_get_contents("php://input"), $putVars);
                            $this->requestParameters->setRequestData($putVars);
                        } else {
                            $input = json_decode(file_get_contents('php://input'));
                            if ($input instanceof stdClass) {
                                $this->requestParameters->setRequestData(VariableHelper::convertStdClassToArray($input));
                            } else $this->requestParameters->setRequestData($input);

                        }
                    }
                    if ($this->cors === true) {
                        header('Access-Control-Allow-Origin:', $this->allowedOrigin);
                    }
                    $task = $route['task'];
                    try {
                        $this->rest->$task($this->requestParameters);
                    } catch (Exception $e) {
                        $this->sendResponse(500, $e->getMessage(), $route['response_is_json']);

                    } catch (Error $e) {
                        $this->sendResponse(500, $e->getMessage(), $route['response_is_json']);
                    }
                    return true;
                }
            }
        }
        if ($this->cors) {
            header('Access-Control-Allow-Origin:', $this->allowedOrigin);
            header('Access-Control-Allow-Headers:', $this->allowedHeaders);
            header('Access-Control-Allow-Methods:', implode(',', $supportedHeaders) . ',OPTIONS');
            header($_SERVER['SERVER_PROTOCOL'] . ' 500');
            echo('NO MATCHING ROOT FOUND');
            return true;
        }
        return false;
    }

    /**
     * compiles and sends http response
     * @param int $httpCode http response code
     * @param mixed $data response data
     * @param bool $responseIsJSon if true, encodes data to JSON
     */
    private function sendResponse(int $httpCode, mixed $data, bool $responseIsJSon)
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $httpCode);
        if ($responseIsJSon === true) {
            header('Content-Type: application/json');
            echo json_encode($data);
//            echo json_encode([$data,debug_backtrace()]);
        } else
            echo $data;
        die();
    }
}
