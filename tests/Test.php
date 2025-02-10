<?php

namespace Tests\Test;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class Test extends TestCase
{
    public function getFixturePath($fixtureName)
    {
        return __DIR__ . "/fixtures/" . $fixtureName;
    }

    public function testDefault()
    {
        $expected = file_get_contents($this->getFixturePath('expected.txt'));
        $firstFile = $this->getFixturePath('file1.json');
        $secondFile = $this->getFixturePath('file2.json');
        $result = genDiff($firstFile, $secondFile);
        $this->assertEquals($expected, $result);
    }

    public function testYmlDiff()
    {
        $expected = file_get_contents($this->getFixturePath('expected.txt'));
        $firstYmlFile = $this->getFixturePath('file1.yml');
        $secondYmlFile = $this->getFixturePath('file2.yaml');
        $ymlDiff = genDiff($firstYmlFile, $secondYmlFile);
        $this->assertEquals($expected, $ymlDiff);
    }

    public function testYmlJsonDiff()
    {
        $expected = file_get_contents($this->getFixturePath('expected.txt'));
        $jsonFile = $this->getFixturePath('file1.json');
        $ymlFile =  $this->getFixturePath('file2.yaml');
        $ymlJsonDiff = genDiff($jsonFile, $ymlFile);
        $this->assertEquals($expected, $ymlJsonDiff);
    }

    public function testStylishFormat()
    {
        $expected = file_get_contents($this->getFixturePath('expected.txt'));
        $firstFile = $this->getFixturePath('file1.json');
        $secondFile = $this->getFixturePath('file2.yaml');
        $result = genDiff($firstFile, $secondFile, "stylish");
        $this->assertEquals($expected, $result);
    }

    public function testPlainFormat()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expectedPlain.txt");
        $firstFile = $this->getFixturePath('file1.json');
        $secondFile = $this->getFixturePath('file2.yaml');
        $result = genDiff($firstFile, $secondFile, "plain");
        $this->assertEquals($expected, $result);
    }

    public function testJsonFormat()
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expectedJson.txt");
        $firstFile = $this->getFixturePath('file1.json');
        $secondFile = $this->getFixturePath('file2.yaml');
        $result = genDiff($firstFile, $secondFile, "json");
        $this->assertEquals($expected, $result);
    }
}
