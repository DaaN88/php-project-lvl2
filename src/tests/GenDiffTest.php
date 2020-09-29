<?php

namespace Tests\Functions\GenDiffTest;

use PHPUnit\Framework\TestCase;

use function Gendiff\Application\Functions\genDiff;

class GenDiffTest extends TestCase
{

    private $pathToFileBeforeJson = 'src/tests/fixtures/testFileOne.json';
    private $pathToFileAfterJson = 'src/tests/fixtures/testFileTwo.json';

    private $pathToFileBeforeYml = 'src/tests/fixtures/testFileOne.yml';
    private $pathToFileAfterYml = 'src/tests/fixtures/testFileTwo.yml';

    /**
     * @dataProvider additionProviderJson
     */
    public static function testOnEqualsJson($expectedValue, $actualValue): void
    {
        self::assertEquals($expectedValue, $actualValue);
    }

    /**
     * @dataProvider additionProviderYml
     */
    public static function testOnEqualsYml($expectedValue, $actualValue): void
    {
        self::assertEquals($expectedValue, $actualValue);
    }


    public function additionProviderJson(): array
    {
        $expectedData = file_get_contents(
            dirname(__DIR__) . "/tests/fixtures/expectedEqualsFirstTest.txt"
        );

        $actual = genDiff($this->pathToFileBeforeJson, $this->pathToFileAfterJson);

        return [
            [$expectedData, $actual],
        ];
    }

    public function additionProviderYml(): array
    {
        $expectedData = file_get_contents(
            dirname(__DIR__) . "/tests/fixtures/expectedEqualsFirstTest.txt"
        );

        $actual = genDiff($this->pathToFileBeforeYml, $this->pathToFileAfterYml);

        return [
          [$expectedData, $actual],
        ];
    }
}
