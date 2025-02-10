<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Formatters\getFormatted;
use function Functional\sort;

function genDiff(string $firstFilePath, string $secondFilePath, string $format = "stylish"): string
{
    $first = parse($firstFilePath);
    $second = parse($secondFilePath);
    $diff = makeDiff($first, $second);
    return getFormatted($diff, $format);
}

function makeDiff(array $first, array $second): array
{
    $keys = array_unique(array_merge(array_keys($first), array_keys($second)));
    $sortedKeys = sort($keys, fn($key1, $key2) => $key1 <=> $key2);
    return array_map(
        fn($key) => makeNode($key, $first, $second),
        $sortedKeys
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

function getNodeWithSameKeys(string $key, mixed $firstValue, mixed $secondValue): array
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

function getUnchangedNode(string $key, mixed $value): array
{
    return [
        'key' => $key,
        'type' => 'unchanged',
        'unchangedValue' => $value
    ];
}

function getChangedNode(string $key, mixed $oldValue, mixed $newValue): array
{
    return [
        'key' => $key,
        'type' => 'changed',
        'oldValue' => $oldValue,
        'newValue' => $newValue,
    ];
}

function getRemovedNode(string $key, mixed $value): array
{
    return [
        'key' => $key,
        'type' => 'removed',
        'oldValue' => $value
    ];
}

function getAddedNode(string $key, mixed $value): array
{
    return [
        'key' => $key,
        'type' => 'added',
        'newValue' => $value
    ];
}
