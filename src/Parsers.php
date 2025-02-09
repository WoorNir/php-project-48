<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $filepath)
{
    if (!is_readable($filepath)) {
        throw new \Exception("Неккоректно задан путь");
    }
    $content = file_get_contents($filepath);
    if ($content === false) {
        throw new \Exception("Не удалось прочитать файл");
    }

    switch (pathinfo($filepath, PATHINFO_EXTENSION)) {
        case 'json':
            return json_decode($content, true);
        case 'yml':
        case 'yaml':
            return Yaml::parse($content);
        default:
            throw new \Exception("Неподдерживаемый формат файла");
    }
}
