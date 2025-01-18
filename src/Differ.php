<?php

namespace src\Differ;

function genDiff($first, $second)
{
    $keys = array_unique(array_merge(array_keys($first), array_keys($second)));
    sort($keys);
    $result = [];
    foreach ($keys as $key) {
        if (array_key_exists($key, $first) && array_key_exists($key, $second)) {
            if ($first[$key] === $second[$key]) {
                $result[] = "   $key:" . " $first[$key]";
            } else {
                $result[] = " - $key:" . "$first[$key]\n" . " + $key:" . "$second[$key]";
            }
        } elseif (array_key_exists($key, $first) && !array_key_exists($key, $second)) {
            $result[] = " - $key:" . "$first[$key]";
        } elseif (!array_key_exists($key, $first) && array_key_exists($key, $second)) {
            $result[] = " + $key:" . "$second[$key]";
        }
    }
    return "{\n" . implode("\n", $result) . "\n}\n";
}
