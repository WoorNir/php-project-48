<?php

namespace Differ\Formatters;

function getFormatted(array $diff, string $format): string
{
    $formatters = [
        'stylish' => \Formatters\Stylish\format(...),
        'plain' => \Formatters\Plain\format(...),
        'json' => \Formatters\Json\format(...),
    ];

    if (!array_key_exists($format, $formatters)) {
        throw new \InvalidArgumentException("Некорректно заданный формат: $format");
    }

    return $formatters[$format]($diff);
}
