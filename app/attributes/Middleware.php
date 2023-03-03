<?php

declare(strict_types = 1);

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Middleware
{
    public function __construct(public array $functions)
    {
        
    }
}