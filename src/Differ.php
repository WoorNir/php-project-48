<?php

namespace Src\Differ;

use function Src\Parser\parse;
use function Src\Stylish\formatter;

function genDiff(string $firstFilePath, string $secondFilePath, $format = "stylish")
{
    $first = parse($firstFilePath);
    $second = parse($secondFilePath);
    $diff = makeDiff($first, $second);
    return formatter($diff, $format);
}

function makeDiff(array $first, array $second): array
{
    $keys = array_unique(array_merge(array_keys($first), array_keys($second)));
    sort($keys);
    return array_map(
        fn($key) => makeNode($key, (array)$first, (array)$second),
        $keys
    );
}

function makeNode(string $key, array $first, array $second): array
{
    if (array_key_exists($key, $first) && array_key_exists($key, $second)) {
        return getNodeWithSameKeys($key, $first[$key], $second[$key]);
    } elseif (array_key_exists($key, $first)) {
        return getRemovedNode($key, $first[$key]);
    } elseif (array_key_exists($key, $second)) {
        return getAddedNode($key, $second[$key]);
    }

    return [];
}

function getNodeWithSameKeys(string $key, $firstValue, $secondValue): array
{
    if (is_array($firstValue) && is_array($secondValue)) {
        return [
            'key' => $key,
            'type' => 'nested',
            'children' => makeDiff($firstValue, $secondValue),
            ];
    } elseif ($firstValue === $secondValue) {
        return getUnchangedNode($key, $firstValue);
    } else {
        return getChangedNode($key, $firstValue, $secondValue);
    }
}

function getUnchangedNode(string $key, $value): array
{
    return [
        'key' => $key,
        'type' => 'unchanged',
        'unchangedValue' => $value
    ];
}

function getChangedNode(string $key, $oldValue, $newValue): array
{
    return [
        'key' => $key,
        'type' => 'changed',
        'oldValue' => $oldValue,
        'newValue' => $newValue,
    ];
}

function getRemovedNode(string $key, $value): array
{
    return [
        'key' => $key,
        'type' => 'removed',
        'oldValue' => $value
    ];
}

function getAddedNode(string $key, $value): array
{
    return [
        'key' => $key,
        'type' => 'added',
        'newValue' => $value
    ];
}
