<?php

namespace Tests\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class Test extends TestCase
{
    #[DataProvider('addDataProvider')]
    public function testDefault($firstFile, $secondFile)
    {
        $expectedResult = file_get_contents($this->getFixturePath('expected.txt'));
        $firstFile = $this->getFixturePath($firstFile);
        $secondFile = $this->getFixturePath($secondFile);
        $result = genDiff($firstFile, $secondFile);
        $this->assertEquals($expectedResult, $result);
    }

    #[DataProvider('addDataProvider')]
    public function testStylishFormat($firstFile, $secondFile)
    {
        $expected = file_get_contents($this->getFixturePath('expected.txt'));
        $firstFile = $this->getFixturePath($firstFile);
        $secondFile = $this->getFixturePath($secondFile);
        $result = genDiff($firstFile, $secondFile, "stylish");
        $this->assertEquals($expected, $result);
    }

    #[DataProvider('addDataProvider')]
    public function testPlainFormat($firstFile, $secondFile)
    {
        $expected = file_get_contents($this->getFixturePath('expectedPlain.txt'));
        $firstFile = $this->getFixturePath($firstFile);
        $secondFile = $this->getFixturePath($secondFile);
        $result = genDiff($firstFile, $secondFile, "plain");
        $this->assertEquals($expected, $result);
    }

    #[DataProvider('addDataProvider')]
    public function testJsonFormat($firstFile, $secondFile)
    {
        $expected = file_get_contents($this->getFixturePath('expectedJson.txt'));
        $firstFile = $this->getFixturePath($firstFile);
        $secondFile = $this->getFixturePath($secondFile);
        $result = genDiff($firstFile, $secondFile, "json");
        $this->assertEquals($expected, $result);
    }

    public function getFixturePath($fixtureName)
    {
        return __DIR__ . "/fixtures/" . $fixtureName;
    }

    public static function addDataProvider()
    {
        return [
            'firstCombination' => [
                'firstFile' => 'file1.json',
                'secondFile' => 'file2.yaml',
            ],
            'secondCombination' => [
                'firstFile' => 'file1.yml',
                'secondFile' => 'file2.json'
            ]
        ];
    }
}
