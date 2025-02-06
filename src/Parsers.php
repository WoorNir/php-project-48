<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $filepath)
{
    if (!is_readable($filepath)) {
        throw new \Exception("Неккоректно задан путь");
    }
    (string)$content = file_get_contents($filepath);
    switch (pathinfo($filepath, $flags = PATHINFO_EXTENSION)) {
        case 'json':
            return json_decode($content, true);
        case 'yml':
        case 'yaml':
            return Yaml::parse($content);
        default:
            echo "Неподдерживаемый формат файла";
            return null;
    }
}
