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
        function ($key) use ($first, $second) {
            if (array_key_exists($key, $first) && array_key_exists($key, $second)) {
                if (is_array($first[$key]) && is_array($second[$key])) {
                    $node = setNode('nested', $key, null, null, makeDiff($first[$key], $second[$key]));
                } elseif ($first[$key] === $second[$key]) {
                    $node = setNode('unchanged', $key, $first[$key], $second[$key]);
                } else {
                    $node = setNode('changed', $key, $first[$key], $second[$key]);
                }
            } elseif (array_key_exists($key, $first)) {
                $node = setNode('removed', $key, $first[$key], null);
            } else {
                $node = setNode('added', $key, null, $second[$key]);
            }
            return $node;
        },
        $sortedKeys
    );
}

function setNode(string $type, string $key, mixed $oldValue, mixed $newValue, array $children = null): array
{
    return [
        'type' => $type,
        'key' => $key,
        'oldValue' => $oldValue,
        'newValue' => $newValue,
        'children' => $children
    ];
}
