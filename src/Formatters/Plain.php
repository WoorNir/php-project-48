<?php

namespace Src\Formatters\Plain;

function getPlain(array $diff, string $path = '')
{
    $lines = getPlainLines($diff, $path);
    $result = array_filter($lines, fn($line) => !is_null($line));
    return implode($result);
}

function getPlainLines(array $diff, string $path): array
{
    return array_map(function ($node) use ($path) {
        $property = $path ? "{$path}.{$node['key']}" : $node['key'];
        switch ($node['type']) {
            case 'nested':
                return getPlain($node['children'], $property);
                break;
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

function formatValue($value)
{
    return match (gettype($value)) {
        'boolean' => $value ? 'true' : 'false',
        'array' => "[complex value]",
        'NULL'=> 'null',
        'string' => "'{$value}'",
        default => (string)$value
    };
}
