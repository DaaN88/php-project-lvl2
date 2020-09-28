<?php

use PHPUnit\Framework\TestCase;
use function Gendiff\Application\Functions\genDiff;

class GenDiffTest extends TestCase
{
    private $pathToFileBefore = 'src/tests/fixtures/testFileOne.json';
    private $pathToFileAfter = 'src/tests/fixtures/testFileTwo.json';

    /**
     * @dataProvider additionProvider
     */
    public static function testOnEquals($expectedValue, $actualValue): void
    {
        self::assertEquals($expectedValue, $actualValue);
    }


    public function additionProvider(): array
    {
        $expectedData = file_get_contents(
            dirname(__DIR__) . "/tests/fixtures/expectedEqualsFirstTest.txt"
        );

        $actual = genDiff($this->pathToFileBefore, $this->pathToFileAfter);

        return [
            [$expectedData, $actual],
        ];
    }
}
