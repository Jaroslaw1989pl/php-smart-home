<?php

declare(strict_types = 1);

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    public function __construct(public string $path, public string $method)
    {
        
    }
}

#[Attribute(Attribute::TARGET_METHOD|Attribute::IS_REPEATABLE)]
class Get extends Route
{
    public function __construct(string $path)
    {
        parent::__construct($path, 'GET');
    }
}

#[Attribute(Attribute::TARGET_METHOD)]
class Post extends Route
{
    public function __construct(string $path)
    {
        parent::__construct($path, 'POST');
    }  
}