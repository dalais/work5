<?php

namespace App\Components;

use App\Components\Interfaces\RequestInterface;

/**
 * Class Router
 *
 * @package App\Components
 */
class Router
{
    private $request;
    private $supportedHttpMethods = array(
        "GET",
        "POST"
    );

    function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    function __call($name, $args)
    {
        list($route, $method) = $args;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHandler();
        }
        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /**
     * Убираем слеш в конце строки.
     * @param $route (string)
     */
    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    private function invalidMethodHandler()
    {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    /**
     * Обработка роута
     */
    public function resolve()
    {
        $methodDictionary = array_merge($this->get,$this->post);
        $formatedRoute = $this->formatRoute($this->request->requestUri);
        $route = '';
        foreach (array_keys($methodDictionary) as $routeReg) {
            preg_match("~" . $routeReg . "~", $formatedRoute, $matches);
            if (in_array($formatedRoute, $matches)) {
                $route = $routeReg;
                break;
            }
        }

        if (!in_array($formatedRoute, $matches)) {
            header('Location: /notfound');
            return;
        }
        $method = $methodDictionary[$route];
        if (is_null($method)) {
            $this->defaultRequestHandler();
            return;
        }

        $handler = explode("@", $method);
        $controller = new $handler[0];
        unset($matches[0]);
        $params = [];
        if (!empty($matches)) {
            $params = $matches;
        }
        if (strtoupper($this->request->requestMethod === 'POST')) {
            array_push($params,$this->request);
        }
        echo call_user_func_array([$controller, $handler[1]], $params);
    }

    function __destruct()
    {
        $this->resolve();
    }
}