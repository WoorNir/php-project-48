<?php

namespace src\Differ;

function genDiff($first, $second)
{
    $keys = array_unique(array_merge(array_keys($first), array_keys($second)));
    sort($keys);
    $result = [];
    foreach ($keys as $key) {
        $result[] = getDiffLine($key, $first, $second);
    }
    return "{\n" . implode("\n", $result) . "\n}\n";
}

function getDiffLine($key, $first, $second)
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

function formatValue($value) {
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}
