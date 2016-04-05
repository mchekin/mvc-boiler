<?php

namespace Mchekin\MVCBoiler\Http;

class Router
{
    /**
     * @var array
     */
    protected $routes;

    /**
     * Router constructor.
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param RequestInterface $request
     * @return array
     * @throws \Exception
     */
    public function resolve(RequestInterface $request)
    {
        $method = strtolower($request->method());
        $path = parse_url($request->server('REQUEST_URI'), PHP_URL_PATH);

        $routes = isset($this->routes[$method]) ? $this->routes[$method] : [];

        foreach ($routes as $pattern => $route) {

            // getting route parameter keys
            preg_match('/\{([a-z]+)\}/', $pattern, $keys);
            array_shift($keys);

            // quote special chars such as { and }
            $regex = '/^'.preg_quote($pattern, '/').'$/';

            // replace the parameter placeholder with a regex for the params
            $regex = preg_replace('/\\\{([a-z]+)\\\}/', '([\d\w]+)', $regex);

            // check if the path matches the route
            if ( preg_match($regex, $path, $matches) ) {
                array_shift($matches);
                return [$route['controller'], $route['action'], array_combine($keys, $matches)];
            }

        }

        throw new \Exception('Invalid path');
    }
}
