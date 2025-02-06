<?php

namespace Src\Formatters\Plain;

function getPlain($diff, $path = '')
{
    $lines = array_map(function ($node) use ($path) {
        $property = $path ? "{$path}.{$node['key']}" : $node['key'];
        switch ($node['type']) {
            case 'nested':
                return getPlain($node['children'], $property);
                break;
            case 'added':
                $value = formatValue($node['newValue']);
                return "Property '{$property}' was added with value: {$value}";
                break;
            case 'removed':
                $value = formatValue($node['oldValue']);
                return "Property '{$property}' was removed";
                break;
            case 'changed':
                $oldValue = formatValue($node['oldValue']);
                $newValue = formatValue($node['newValue']);
                return "Property '{$property}' was updated. From {$oldValue} to {$newValue}";
                break;
            case 'unchanged':
                return null;
                break;
            default:
                throw new \Exception("Ошибка определения типа узла");
        };
    },
    $diff);

    $lines = array_filter($lines, fn($line) => !is_null($line));
    return implode("\n", $lines);
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
