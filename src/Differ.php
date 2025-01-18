<?php

namespace src\Differ;

function genDiff($first, $second)
{
    $keys = array_unique(array_merge(array_keys($first), array_keys($second)));
    sort($keys);
    $result = [];
    foreach ($keys as $key) {
        $result[] = getDiffLine($key, $first, $second);
    }
    return "{\n" . implode("\n", $result) . "\n}\n";
}

function getDiffLine($key, $first, $second)
{
    $line = '';
    if (array_key_exists($key, $first) && array_key_exists($key, $second)) {
        if ($first[$key] === $second[$key]) {
            $line = "   $key:" . " $first[$key]";
        } else {
            $line = " - $key:" . "$first[$key]\n" . " + $key:" . "$second[$key]";
        }
    } elseif (array_key_exists($key, $first) && !array_key_exists($key, $second)) {
        $line = " - $key:" . "$first[$key]";
    } elseif (!array_key_exists($key, $first) && array_key_exists($key, $second)) {
        $line = " + $key:" . "$second[$key]";
    }
    return $line;
}
