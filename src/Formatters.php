<?php

namespace Src\Formatters;

use function Src\Formatters\Plain\getPlain;
use function Src\Formatters\Stylish\getStylish;
use function Src\Formatters\Json\getJson;

function formatter($diff, $format)
{
    return match ($format) {
        'stylish' => getStylish($diff),
        'plain' => getPlain($diff),
        'json' => getJson($diff),
        default => throw new \InvalidArgumentException("Некорректно заданный формат")
    };
}
