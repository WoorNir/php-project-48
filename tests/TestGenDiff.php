<?php

namespace Tests\TestGenDiff;

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testDiff()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $firstFile = file_get_contents(__DIR__ . "fixtures/file1.json");
        $secondFile = file_get_contents(__DIR__ . "fixtures/dile2.json");
        $result = genDiff($firstFile, $secondFile);
        $this->assertEquals($expected, $result);
    }
}
