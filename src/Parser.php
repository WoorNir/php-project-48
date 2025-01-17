<?php

namespace src\Parser;

function parse($file)
{
    return json_decode(file_get_contents($file));
}