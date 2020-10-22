<?php

namespace Gendiff\Tests\Functions;

use PHPUnit\Framework\TestCase;

use function Gendiff\Engine\genDiff\genDiff;

class GenDiffTest extends TestCase
{
    private $pathToFileBeforeJson = 'Tests/fixtures/testFileNestedOne.json';
    private $pathToFileAfterJson = 'Tests/fixtures/testFileNestedTwo.json';

    private $pathToFileBeforeYml = 'Tests/fixtures/testFileNestedOne.yml';
    private $pathToFileAfterYml = 'Tests/fixtures/testFileNestedTwo.yml';

    /**
     * @dataProvider additionProviderPrettyFormat
     *
     * @param $expectedValue
     * @param $actualValue
     */
    public static function testEqualsPrettyFormat($expectedValue, $actualValue): void
    {
        self::assertEquals($expectedValue, $actualValue);
    }

    /**
     * @dataProvider additionProviderPlainFormat
     *
     * @param $expectedValue
     * @param $actualValue
     */
    public static function testEqualsPlainFormat($expectedValue, $actualValue): void
    {
        self::assertEquals($expectedValue, $actualValue);
    }

    /**
     * @dataProvider additionProviderJsonFormat
     *
     * @param $expectedValue
     * @param $actualValue
     */
    public static function testEqualsJsonFormat($expectedValue, $actualValue): void
    {
        self::assertEquals($expectedValue, $actualValue);
    }

    public function additionProviderPrettyFormat(): array
    {
        $expectedDataJson = file_get_contents(
            dirname(__DIR__) . "/fixtures/expectedEqualsPrettyFormatForJson.txt"
        );
        $expectedDataYml = file_get_contents(
            dirname(__DIR__) . "/fixtures/expectedEqualsPrettyFormatForYml.txt"
        );

        $actualJson = genDiff($this->pathToFileBeforeJson, $this->pathToFileAfterJson, 'pretty');
        $actualYml = genDiff($this->pathToFileBeforeYml, $this->pathToFileAfterYml, 'pretty');

        return [
            [$expectedDataJson, $actualJson],
            [$expectedDataYml, $actualYml],
        ];
    }

    public function additionProviderPlainFormat(): array
    {
        $expectedDataJson = file_get_contents(
            dirname(__DIR__) . "/fixtures/expectedEqualsPlainFormatForJson.txt"
        );
        $expectedDataYml = file_get_contents(
            dirname(__DIR__) . "/fixtures/expectedEqualsPlainFormatForYml.txt"
        );

        $actualJson = genDiff($this->pathToFileBeforeJson, $this->pathToFileAfterJson, 'plain');
        $actualYml = genDiff($this->pathToFileBeforeYml, $this->pathToFileAfterYml, 'plain');

        return [
            [$expectedDataJson, $actualJson],
            [$expectedDataYml, $actualYml],
        ];
    }

    public function additionProviderJsonFormat(): array
    {
        $expectedDataJson = file_get_contents(
            dirname(__DIR__) . "/fixtures/expectedEqualsJsonFormatForJson.json"
        );
        $expectedDataYml = file_get_contents(
            dirname(__DIR__) . "/fixtures/expectedEqualsJsonFormatForYml.json"
        );

        $actualJson = genDiff($this->pathToFileBeforeJson, $this->pathToFileAfterJson, 'json');
        $actualYml = genDiff($this->pathToFileBeforeYml, $this->pathToFileAfterYml, 'json');

        return [
            [$expectedDataJson, $actualJson],
            [$expectedDataYml, $actualYml],
        ];
    }
}
