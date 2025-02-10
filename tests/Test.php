<?php

namespace Tests\Test;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class Test extends TestCase
{
    public function testDiff()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $firstFile = __DIR__ . "/fixtures/file1.json";
        $secondFile = __DIR__ . "/fixtures/file2.json";
        $result = genDiff($firstFile, $secondFile);
        $this->assertEquals($expected, $result);
    }

    public function testYmlDiff()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $firstYmlFile = __DIR__ . "/fixtures/file1.yml";
        $secondYmlFile = __DIR__ . "/fixtures/file2.yml";
        $ymlDiff = genDiff($firstYmlFile, $secondYmlFile);
        $this->assertEquals($expected, $ymlDiff);
    }

    public function testYmlJsonDiff()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $jsonFile = __DIR__ . "/fixtures/file1.json";
        $ymlFile = __DIR__ . "/fixtures/file2.yml";
        $ymlJsonDiff = genDiff($jsonFile, $ymlFile);
        $this->assertEquals($expected, $ymlJsonDiff);
    }

    public function testStylishFormat()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $firstFile = __DIR__ . "/fixtures/file1.json";
        $secondFile = __DIR__ . "/fixtures/file2.yml";
        $result = genDiff($firstFile, $secondFile, "stylish");
        $this->assertEquals($expected, $result);
    }

    public function testPlainFormat()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expectedPlain.txt");
        $firstFile = __DIR__ . "/fixtures/file1.json";
        $secondFile = __DIR__ . "/fixtures/file2.yml";
        $result = genDiff($firstFile, $secondFile, "plain");
        $this->assertEquals($expected, $result);
    }

    public function testJsonFormat()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expectedJson.txt");
        $firstFile = __DIR__ . "/fixtures/file1.json";
        $secondFile = __DIR__ . "/fixtures/file2.yml";
        $result = genDiff($firstFile, $secondFile, "json");
        $this->assertEquals($expected, $result);
    }
}
