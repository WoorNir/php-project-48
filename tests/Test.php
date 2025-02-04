<?php

namespace Tests\Test;

use PHPUnit\Framework\TestCase;

use function Src\Differ\genDiff;

class Test extends TestCase
{
    public function testDiff()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $firstFile = __DIR__ . "/fixtures/file1.json";
        $secondFile = __DIR__ . "/fixtures/file2.json";
        $result = genDiff($firstFile, $secondFile);
        $this->assertEquals($expected, $result);

        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $firstFile = __DIR__ . "/fixtures/file1.json";
        $secondFile = __DIR__ . "/fixtures/file2.yml";
        $result = genDiff($firstFile, $secondFile);
        $this->assertEquals($expected, $result);

        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $firstFile = __DIR__ . "/fixtures/file1.yml";
        $secondFile = __DIR__ . "/fixtures/file2.yml";
        $result = genDiff($firstFile, $secondFile);
        $this->assertEquals($expected, $result);
    }
}
