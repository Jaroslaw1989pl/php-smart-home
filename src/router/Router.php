<?php

declare(strict_types = 1);

namespace src\router;

use app\Request;
use app\Response;


class Router
{
    private static string $route;
    private static array $routes = [];

    // public static function attributeRouting(array $controllers): void
    // {
    //     // loop through controllers list received from method argument
    //     foreach ($controllers as $controller)
    //     {
    //         // create ReflectionClass object foreach controller
    //         $reflectionController = new \ReflectionClass($controller);
    //         // loop through controller methods
    //         foreach ($reflectionController->getMethods() as $method)
    //         {
    //             // access to (Middleware) attributes that are associated with method
    //             $middlewareAttributes = $method->getAttributes(Middleware::class);
    //             $middleware = [];
    //             foreach ($middlewareAttributes as $attribute)
    //             {
    //                 // instantiate the (Middleware) attribute class
    //                 $middleware = $attribute->newInstance()->functions;
    //             }
    //             // access to (instance of Route) attributes that are associated with method
    //             $routeAttributes = $method->getAttributes(Route::class, \ReflectionAttribute::IS_INSTANCEOF);
    //             // loop through method (Route) attributes
    //             foreach ($routeAttributes as $attribute)
    //             {
    //                 // instantiate the (instance of Route) attribute class
    //                 $route = $attribute->newInstance();

    //                 self::$routes[$route->method][$route->path] = [
    //                     'middleware' => $middleware,
    //                     'class' => $controller,
    //                     'method' => $method->getName()
    //                 ];
    //             }
    //         }
    //     }
    //     // echo '<pre>';
    //     // var_dump(self::$routes);
    //     // exit();
    // }

    public static function __callStatic($name, $arguments)
    {
        self::$routes['GET'][$name] = [
            'class'  => $arguments[0],
            'method' => $arguments[1]
        ];
        return new static();
    }

    public static function route(string $path): Router
    {
        self::$route = $path;
        return new static();
    }

    public static function get(array $middleware, array $action): Router
    {
        self::$routes['GET'][self::$route] = [
            'middleware' => $middleware,
            'class'      => $action[0],
            'method'     => $action[1]
        ];
        return new static();
    }

    public static function post(array $middleware, array $action): Router
    {
        self::$routes['POST'][self::$route] = [
            'middleware' => $middleware,
            'class'      => $action[0],
            'method'     => $action[1]
        ];
        return new static();
    }

    public function statics(): void
    {
        if (str_starts_with(Request::path(), '/statics/'))
            self::$routes[Request::method()][Request::path()] = substr(Request::path(), 1);
        // else if (stristr(Request::path(), 'favicon.ico'))
        //     self::$routes[Request::method()][Request::path()] = substr(Request::path(), 1);
    }

    public function run(): void
    {
        $callback = self::$routes[Request::method()][Request::path()];

        if (is_string($callback))
        {
            Response::responseHeaders();
            include_once $callback;
        }
        else if (is_array($callback))
        {
            if ($middleware = $callback['middleware'])
                foreach ($middleware as $function)
                    call_user_func($function);

            call_user_func([new $callback['class'](), $callback['method']], Request::body());
        }
        else
        {
            $callback = self::$routes['GET']['not_found'];

            Response::statusCode(404);
            call_user_func([new $callback['class'](), $callback['method']], Request::body());
        }
    }
}