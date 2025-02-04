<?php

namespace Src\Differ;

use function Src\Parser\parse;
use function Src\Stylish\stylish;

function genDiff($firstFilePath, $secondFilePath, $format = "stylish")
{
    $first = parse($firstFilePath);
    $second = parse($secondFilePath);
    $diff = makeDiff($first, $second);

    if ($format === "stylish") {
        return stylish($diff);
    }

    throw new \Exception("Некорректно задан формат");
}

function makeDiff($first, $second)
{
    $keys = array_unique(array_merge(array_keys((array)$first), array_keys((array)$second)));
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

function getUnchangedNode($key, $value): array
{
    return [
        'key' => $key,
        'type' => 'unchanged',
        'unchangedValue' => $value
    ];
}

function getChangedNode($key, $oldValue, $newValue): array
{
    return [
        'key' => $key,
        'type' => 'changed',
        'oldValue' => $oldValue,
        'newValue' => $newValue,
    ];
}

function getRemovedNode($key, $value): array
{
    return [
        'key' => $key,
        'type' => 'removed',
        'oldValue' => $value
    ];
}

function getAddedNode($key, $value): array
{
    return [
        'key' => $key,
        'type' => 'added',
        'newValue' => $value
    ];
}
