<?php

namespace Differ\Formatters\Stylish;

const SPACE_COUNTS = 4;

function getStylish(array $diff)
{
    $body = makeBody($diff);
    return "{\n{$body}\n}\n";
}

function makeBody(array $diff, int $depth = 0): string
{
    $body = array_reduce(
        $diff,
        function ($acc, array $node) use ($depth) {
            $acc = array_merge($acc, [match ($node['type']) {
                'unchanged' => stylishUnchangedValue($node, $depth),
                'added' => stylishAddedValue($node, $depth),
                'removed' => stylishRemovedValue($node, $depth),
                'changed' => stylishChangedValue($node, $depth),
                'nested' => stylishNestedValue($node, $depth),
                default => throw new \Exception("Ошибка определения типа узла")
            }]);
            return $acc;
        },
        []
    );
    return implode("\n", $body);
}

function formatValue(mixed $value, int $depth)
{
    return match (gettype($value)) {
        'boolean' => $value ? 'true' : 'false',
        'array' => stylishArray($value, $depth + 1),
        'NULL'=> 'null',
        default => $value
    };
}

function stylishArray(array $array, int $depth)
{
    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
    $keys = array_keys($array);
    $bodyAsArray = array_map(
        function ($key) use ($array, $depth, $indent) {
            $value = formatValue($array[$key], $depth);
            return "{$indent}    {$key}: {$value}";
        },
        $keys
    );
    $bodyAsString = implode("\n", $bodyAsArray);
    return "{\n{$bodyAsString}\n{$indent}}";
}

function stylishAddedValue(array $node, int $depth)
{
    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
    $value = formatValue($node['newValue'], $depth);
    return "{$indent}  + {$node['key']}: $value";
}

function stylishRemovedValue(array $node, int $depth)
{
    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
    $value = formatValue($node['oldValue'], $depth);
    return "{$indent}  - {$node['key']}: $value";
}

function stylishUnchangedValue(array $node, int $depth)
{
    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
    $value = formatValue($node['unchangedValue'], $depth);
    return "{$indent}    {$node['key']}: $value";
}

function stylishNestedValue(array $node, int $depth)
{
    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
    $innerIndent = str_repeat(' ', ($depth + 1) * SPACE_COUNTS);
    $body = makeBody($node['children'], $depth + 1);
    return "{$indent}    {$node['key']}: {\n{$body}\n{$innerIndent}}";
}

function stylishChangedValue(array $node, int $depth)
{
    $result = array_merge([stylishRemovedValue($node, $depth)], [stylishAddedValue($node, $depth)]);
    return implode("\n", $result);
}
