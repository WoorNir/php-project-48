<?php

namespace Src\Differ;

use function Src\Parser\parse;

function genDiff($firstFilePath, $secondFilePath)
{
    $first = parse($firstFilePath);
    $second = parse($secondFilePath);
    return makeDiff($first, $second);
}

function makeDiff($first, $second)
{
    $keys = array_unique(array_merge(array_keys((array)$first), array_keys((array)$second)));
    sort($keys);
    $result = [];
    foreach ($keys as $key) {
        $result[] = getDiffLines($key, (array) $first, (array) $second);
    }
    return "{\n" . implode("\n", $result) . "\n}\n";
}

function getDiffLines($key, array $first, array $second): string
{
    $line = '';
    if (array_key_exists($key, $first) && array_key_exists($key, $second)) {
        $line = getLineIfTwoExists($key, $first[$key], $second[$key]);
    } elseif (array_key_exists($key, $first) && !array_key_exists($key, $second)) {
        $line = getLineIfFirstExists($key, $first[$key]);
    } elseif (!array_key_exists($key, $first) && array_key_exists($key, $second)) {
        $line = getLineIfSecondExists($key, $second[$key]);
    }
    return $line;
}

function getLineIfTwoExists($key, $firstValue, $secondValue)
{
    if ($firstValue === $secondValue) {
        return "   $key: " . formatValue($firstValue);
    } else {
        return " - $key: " . formatValue($firstValue) . "\n" . " + $key: " . formatValue($secondValue);
    }
}

function getLineIfFirstExists($key, $value)
{
    return " - $key: " . formatValue($value);
}

function getLineIfSecondExists($key, $value)
{
    return " + $key: " . formatValue($value);
}

function formatValue($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}
