<?php

namespace src\Differ;


function genDiff($first, $second)
{
    $keys = array_unique(array_merge(array_keys($first), array_keys($second)));
    sort($keys);
    $result = array_map(function ($key) use ($first, $second) {
        if (array_key_exists($key, $first) && array_key_exists($key, $second)) {
            if ($first[$key] === $second[$key]) {
                return "   $key:" . " $first[$key]";
            } else {
                return " - $key:" . "$first[$key]\n" . " + $key:" . "$second[$key]";
            }
        } elseif (array_key_exists($key, $first) && !array_key_exists($key, $second)) {
            return " - $key:" . "$first[$key]";
        } elseif (!array_key_exists($key, $first) && array_key_exists($key, $second)) {
            return " + $key:" . "$second[$key]";
        }
    }, $keys);
    $result = "{\n" . implode("\n", $result) . "\n}\n";
    return $result;
}
