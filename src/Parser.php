<?php

namespace Src\Parser;

function parse($filepath)
{
    $content = file_get_contents($filepath);
    return json_decode($content, true);
}
