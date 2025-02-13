<?php

namespace Formatters\Json;

function format(array $diff): string
{
    return json_encode($diff);
}
