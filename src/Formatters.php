<?php

namespace Src\Formatters;

use function Src\Formatters\Plain\getPlain;
use function Src\Formatters\Stylish\getStylish;

function formatter($diff, $format)
{
    return match ($format) {
        'stylish' => getStylish($diff),
        'plain' => getPlain($diff)
    };
}
