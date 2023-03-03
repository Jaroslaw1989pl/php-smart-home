<?php

declare(strict_types = 1);

namespace src\middleware;

use app\Request;
use app\Response;
use app\Session;


interface Middleware
{
    public function proccess(Request $request, \Closure $next);
}

class BeforeMiddleware implements Middleware
{
    public function __construct()
    {
        
    }

    public function proccess(Request $request, \Closure $next)
    {
        
    }
}

class AfterMiddleware implements Middleware
{
    public function __construct()
    {
        
    }
    
    public function proccess(Request $request, \Closure $next)
    {

    }
}