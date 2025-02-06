<?php

namespace Differ\Formatters\Json;

function getJson(array $diff): string
{
    return json_encode($diff);
}
