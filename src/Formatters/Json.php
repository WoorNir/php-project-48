<?php

namespace Src\Formatters\Json;

function getJson(array $diff): string
{
    return json_encode($diff);
}
