<?php

namespace Formatters\Stylish;

const SPACE_COUNTS = 4;

function format(array $diff): string
{
    $body = makeBody($diff);
    return "{\n{$body}\n}\n";
}

function makeBody(array $diff, int $depth = 0)
{
    $body = array_reduce(
        $diff,
        function ($acc, array $node) use ($depth) {
            switch ($node['type']) {
                case 'unchanged':
                    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
                    $value = formatValue($node['oldValue'], $depth);
                    $bodyElement = "{$indent}    {$node['key']}: $value";
                    break;
                case 'added':
                    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
                    $value = formatValue($node['newValue'], $depth);
                    $bodyElement = "{$indent}  + {$node['key']}: $value";
                    break;
                case 'removed':
                    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
                    $value = formatValue($node['oldValue'], $depth);
                    $bodyElement = "{$indent}  - {$node['key']}: $value";
                    break;
                case 'changed':
                    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
                    $oldValue = formatValue($node['oldValue'], $depth);
                    $newValue = formatValue($node['newValue'], $depth);
                    $bodyElement = "{$indent}  - {$node['key']}: $oldValue\n{$indent}  + {$node['key']}: $newValue";
                    break;
                case 'nested':
                    $indent = str_repeat(' ', $depth * SPACE_COUNTS);
                    $innerIndent = str_repeat(' ', ($depth + 1) * SPACE_COUNTS);
                    $body = makeBody($node['children'], $depth + 1);
                    $bodyElement = "{$indent}    {$node['key']}: {\n{$body}\n{$innerIndent}}";
                    break;
                default:
                    throw new \Exception("Ошибка определения типа узла");
            };
            return [...$acc, $bodyElement];
        },
        []
    );
    return implode("\n", $body);
}

function formatValue(mixed $value, int $depth): string
{
    return match (gettype($value)) {
        'boolean' => $value ? 'true' : 'false',
        'array' => formatArray($value, $depth + 1),
        'NULL'=> 'null',
        default => (string)$value
    };
}

function formatArray(array $array, int $depth)
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
