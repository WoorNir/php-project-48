<?php

namespace Src\Stylish;

function stylish($diff)
{
    $body = makeBody($diff);
    return "{\n{$body}\n}\n";
}

function makeBody($diff, $depth = 0)
{
    $body = array_reduce(
        $diff,
        function ($acc, $node) use ($depth) {
            switch ($node['type']) {
                case 'unchanged':
                    $acc[] = stylishUnchangedValue($node, $depth);
                    break;
                case 'added':
                    $acc[] = stylishAddedValue($node, $depth);
                    break;
                case 'removed':
                    $acc[] = stylishRemovedValue($node, $depth);
                    break;
                case 'changed':
                    $acc[] = stylishRemovedValue($node, $depth);
                    $acc[] = stylishAddedValue($node, $depth);
                    break;
                case 'nested':
                    $acc[] = stylishNestedValue($node, $depth);
                    break;
                default:
                    throw new \Exception("Ошибка определения типа узла");
            }
            return $acc;
        },
        []
    );

    return implode("\n", $body);
}

function formatValue($value, $depth)
{
    switch (gettype($value)) {
        case 'boolean':
            return $value ? 'true' : 'false';
        case 'array':
            return stylishArray($value, $depth + 1);
        case 'NULL':
            return 'null';
        default:
            return $value;
    }
}

function stylishArray($array, $depth)
{
    $indent = str_repeat(' ', $depth * 4);
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
    $indent = str_repeat(' ', $depth * 4);
    $value = formatValue($node['newValue'], $depth);
    return "{$indent}  + {$node['key']}: $value";
}

function stylishRemovedValue($node, $depth)
{
    $indent = str_repeat(' ', $depth * 4);
    $value = formatValue($node['oldValue'], $depth);
    return "{$indent}  - {$node['key']}: $value";
}

function stylishUnchangedValue($node, $depth)
{
    $indent = str_repeat(' ', $depth * 4);
    $value = formatValue($node['unchangedValue'], $depth);
    return "{$indent}    {$node['key']}: $value";
}

function stylishNestedValue($node, $depth)
{
    $indent = str_repeat(' ', $depth * 4);
    $innerIndent = str_repeat(' ', ($depth + 1) * 4);
    $body = makeBody($node['children'], $depth + 1);
    return "{$indent}    {$node['key']}: {\n{$body}\n{$innerIndent}}";
}
