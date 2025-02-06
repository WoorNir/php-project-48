<?php

namespace Differ\Formatters\Plain;

function getPlain(array $diff, string $path = '')
{
    $lines = getPlainLines($diff, $path);
    return implode($lines);
}

function getPlainLines(array $diff, string $path): array
{
    return array_map(function ($node) use ($path) {
        $property = $path ? "{$path}.{$node['key']}" : $node['key'];
        switch ($node['type']) {
            case 'nested':
                return getPlain($node['children'], $property);
            case 'added':
                $value = formatValue($node['newValue']);
                return "Property '{$property}' was added with value: {$value}\n";
                break;
            case 'removed':
                $value = formatValue($node['oldValue']);
                return "Property '{$property}' was removed\n";
                break;
            case 'changed':
                $oldValue = formatValue($node['oldValue']);
                $newValue = formatValue($node['newValue']);
                return "Property '{$property}' was updated. From {$oldValue} to {$newValue}\n";
                break;
            case 'unchanged':
                break;
            default:
                throw new \Exception("Ошибка определения типа узла");
        };
    },
    $diff);
}

function formatValue(mixed $value)
{
    return match (gettype($value)) {
        'boolean' => $value ? 'true' : 'false',
        'array' => "[complex value]",
        'NULL'=> 'null',
        'string' => "'{$value}'",
        default => (string)$value
    };
}
