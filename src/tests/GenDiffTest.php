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

    private $pathToFileNestedBeforeJson = 'src/tests/fixtures/testFileNestedOne.json';
    private $pathToFileNestedAfterJson = 'src/tests/fixtures/testFileNestedTwo.json';

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

    /**
     * @dataProvider additionProviderNestedJson
     */
    public static function testOnNestedEqualsJson($expectedValue, $actualValue): void
    {
        self::assertEquals($expectedValue, $actualValue);
    }

    /**
     * @dataProvider additionProviderPlainFormat
     */
    public static function testEqualsPlainFormat($expectedValue, $actualValue): void
    {
        self::assertEquals($expectedValue, $actualValue);
    }


    public function additionProviderJson(): array
    {
        $expectedData = file_get_contents(
            dirname(__DIR__) . "/tests/fixtures/expectedEqualsFirstTest.txt"
        );

        $actual = genDiff($this->pathToFileBeforeJson, $this->pathToFileAfterJson, 'string');

        return [
            [$expectedData, $actual],
        ];
    }

    public function additionProviderYml(): array
    {
        $expectedData = file_get_contents(
            dirname(__DIR__) . "/tests/fixtures/expectedEqualsFirstTest.txt"
        );

        $actual = genDiff($this->pathToFileBeforeYml, $this->pathToFileAfterYml, 'string');

        return [
          [$expectedData, $actual],
        ];
    }

    public function additionProviderNestedJson(): array
    {
        $expectedData = file_get_contents(
            dirname(__DIR__) . "/tests/fixtures/expectedEqualsForNested.txt"
        );

        $actual = genDiff($this->pathToFileNestedBeforeJson, $this->pathToFileNestedAfterJson, 'string');

        return [
            [$expectedData, $actual],
        ];
    }

    public function additionProviderPlainFormat(): array
    {
        $expectedDataNested = file_get_contents(
            dirname(__DIR__) . "/tests/fixtures/expectedEqualsPlainForNested.txt"
        );

        $expectedDataJson = file_get_contents(
            dirname(__DIR__) . "/tests/fixtures/expectedEqualsPlainJson.txt"
        );

        $expectedDataYml = file_get_contents(
            dirname(__DIR__) . "/tests/fixtures/expectedEqualsPlainYml.txt"
        );

        $actualNested = genDiff($this->pathToFileNestedBeforeJson, $this->pathToFileNestedAfterJson, 'plain');
        $actualJson = genDiff($this->pathToFileBeforeJson, $this->pathToFileAfterJson, 'plain');
        $actualYml = genDiff($this->pathToFileBeforeYml, $this->pathToFileAfterYml, 'plain');

        return [
            [$expectedDataNested, $actualNested],
            [$expectedDataJson, $actualJson],
            [$expectedDataYml, $actualYml],
        ];
    }
}
