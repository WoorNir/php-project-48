<?php

namespace Src\Formatters\Stylish;

const SPACE_COUNTS = 4;

function getStylish(array $diff)
{
    $body = makeBody($diff);
    return "{\n{$body}\n}\n";
}

function makeBody(array $diff, $depth = 0): string
{
    $body = array_reduce(
        $diff,
        function ($acc, $node) use ($depth) {
            $acc[] = match ($node['type']) {
                'unchanged' => stylishUnchangedValue($node, $depth),
                'added' => stylishAddedValue($node, $depth),
                'removed' => stylishRemovedValue($node, $depth),
                'changed' => stylishChangedValue($node, $depth),
                'nested' => stylishNestedValue($node, $depth),
                default => throw new \Exception("Ошибка определения типа узла")
            };
            return $acc;
        },
        []
    );
    return implode("\n", $body);
}

function formatValue($value, $depth)
{
    return match (gettype($value)) {
        'boolean' => $value ? 'true' : 'false',
        'array' => stylishArray($value, ++$depth),
        'NULL'=> 'null',
        default => $value
    };
}

function stylishArray($array, $depth)
{
    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
    $keys = array_keys($array);
    $body = array_map(
        function ($key) use ($array, $depth, $indent) {
            $value = formatValue($array[$key], $depth);
            return "{$indent}    {$key}: {$value}";
        },
        $keys
    );
    $body = implode("\n", $body);
    return "{\n{$body}\n{$indent}}";
}

function stylishAddedValue($node, $depth)
{
    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
    $value = formatValue($node['newValue'], $depth);
    return "{$indent}  + {$node['key']}: $value";
}

function stylishRemovedValue($node, $depth)
{
    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
    $value = formatValue($node['oldValue'], $depth);
    return "{$indent}  - {$node['key']}: $value";
}

function stylishUnchangedValue($node, $depth)
{
    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
    $value = formatValue($node['unchangedValue'], $depth);
    return "{$indent}    {$node['key']}: $value";
}

function stylishNestedValue($node, $depth)
{
    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
    $innerIndent = str_repeat(' ', (++$depth) * SPACE_COUNTS);
    $body = makeBody($node['children'], $depth);
    return "{$indent}    {$node['key']}: {\n{$body}\n{$innerIndent}}";
}

function stylishChangedValue($node, $depth)
{
    $result[] = stylishRemovedValue($node, $depth);
    $result[] = stylishAddedValue($node, $depth);
    return implode("\n", $result);
}
