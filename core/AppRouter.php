<?php

namespace App\Core;

class AppRouter
{

    public $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    public function __construct(array $routes = [])
    {
        $this->load($routes);
    }

    public function load($routes)
    {
        $router = $this;

        foreach ($routes as $group => $listing) {
            if ($listing && file_exists($listing)) {
                require $listing;
            }
        }
    }

    public function get($uri, $controller)
    {

        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller)
    {

        $this->routes['POST'][$uri] = $controller;
    }

    public function put($uri, $controller)
    {

        $this->routes['PUT'][$uri] = $controller;
    }

    public function delete($uri, $controller)
    {

        $this->routes['DELETE'][$uri] = $controller;
    }

    public function direct($uri, $requestType)
    {
        if (array_key_exists($uri, $this->routes[$requestType])) {
            return $this->callAction(
                ...explode('@', $this->routes[$requestType][$uri])
            );
        }

        $urlWithArgs = preg_split("/\/(?!.*\/)/", $uri);
        
        if (array_key_exists($urlWithArgs[0], $this->routes[$requestType])) {
            return $this->callAction(
                ...array_merge(explode('@', $this->routes[$requestType][$urlWithArgs[0]]), [$urlWithArgs[1],])
            );
        }
        throw new \Exception('Invalid URL.');
    }

    private function callAction($controller, $action, $argument = null)
    {
        $controller = "App\\Controllers\\Api\\{$controller}";
        $controllerObject = new $controller;

        if (!method_exists($controllerObject, $action)) {
            throw new \Exception(
                "{$controller} does not respond to the {$action} action."
            );
        }

        return $controllerObject->$action($argument);
    }
}
