<?php

declare(strict_types = 1);

namespace app\enumerators;


enum CommandLineEnums: string
{
    // text colors
    case Blue  = "\033[34m";
    case Green = "\033[32m";
    case Red   = "\033[31m";
    
    // text decoration
    case Bold   = "\033[1m";
    case Italic = "\033[3m";
    
    // normal style
    case End = "\033[0m";
}