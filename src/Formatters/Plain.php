<?php

namespace Formatters\Plain;

function format(array $diff, string $path = ''): string
{
    $lines = getPlainLines($diff, $path);
    return implode($lines);
}

function getPlainLines(array $diff, string $path): array
{
    return array_map(function ($node) use ($path) {
        $currentPath = $path === '' ? $node['key'] : "{$path}.{$node['key']}";

        switch ($node['type']) {
            case 'nested':
                return format($node['children'], $currentPath);
            case 'added':
                $value = formatValue($node['newValue']);
                return "Property '{$currentPath}' was added with value: {$value}\n";
            case 'removed':
                return "Property '{$currentPath}' was removed\n";
            case 'changed':
                $oldValue = formatValue($node['oldValue']);
                $newValue = formatValue($node['newValue']);
                return "Property '{$currentPath}' was updated. From {$oldValue} to {$newValue}\n";
            case 'unchanged':
                return '';
            default:
                throw new \Exception("Ошибка определения типа узла");
        };
    }, $diff);
}

function formatValue(mixed $value): string
{
    return match (gettype($value)) {
        'boolean' => $value ? 'true' : 'false',
        'array' => "[complex value]",
        'NULL'=> 'null',
        'string' => "'{$value}'",
        default => (string)$value
    };
}
