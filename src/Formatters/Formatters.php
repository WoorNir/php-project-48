<?php

namespace Src\Formatters;

use function SrcFormatters\Stylish\getStylish;

function formatter($diff, $format)
{
    return match ($format) {
        'stylish' => getStylish($diff),
        'plain' => 
    }
}