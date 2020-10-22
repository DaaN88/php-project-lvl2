<?php

namespace Gendiff\Tests;

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
     * @param $expectedDataJson
     * @param $expectedDataYml
     */
    public function testEqualsPrettyFormat($expectedDataJson, $expectedDataYml): void
    {
        $actualJson = genDiff($this->pathToFileBeforeJson, $this->pathToFileAfterJson, 'pretty');
        $actualYml = genDiff($this->pathToFileBeforeYml, $this->pathToFileAfterYml, 'pretty');

        self::assertEquals($expectedDataJson, $actualJson);
        self::assertEquals($expectedDataYml, $actualYml);
    }

    /**
     * @dataProvider additionProviderPlainFormat
     *
     * @param $expectedDataJson
     * @param $expectedDataYml
     */
    public function testEqualsPlainFormat($expectedDataJson, $expectedDataYml): void
    {
        $actualJson = genDiff($this->pathToFileBeforeJson, $this->pathToFileAfterJson, 'plain');
        $actualYml = genDiff($this->pathToFileBeforeYml, $this->pathToFileAfterYml, 'plain');

        self::assertEquals($expectedDataJson, $actualJson);
        self::assertEquals($expectedDataYml, $actualYml);
    }

    /**
     * @dataProvider additionProviderJsonFormat
     *
     * @param $expectedDataJson
     * @param $expectedDataYml
     */
    public function testEqualsJsonFormat($expectedDataJson, $expectedDataYml): void
    {
        $actualJson = genDiff($this->pathToFileBeforeJson, $this->pathToFileAfterJson, 'json');
        $actualYml = genDiff($this->pathToFileBeforeYml, $this->pathToFileAfterYml, 'json');

        self::assertEquals($expectedDataJson, $actualJson);
        self::assertEquals($expectedDataYml, $actualYml);
    }

    public function additionProviderPrettyFormat(): array
    {
        $expectedDataJson = file_get_contents(
            dirname(__DIR__) . "/Tests/fixtures/expectedEqualsPrettyFormatForJson.txt"
        );
        $expectedDataYml = file_get_contents(
            dirname(__DIR__) . "/Tests/fixtures/expectedEqualsPrettyFormatForYml.txt"
        );

        return [
            [$expectedDataJson, $expectedDataYml],
        ];
    }

    public function additionProviderPlainFormat(): array
    {
        $expectedDataJson = file_get_contents(
            dirname(__DIR__) . "/Tests/fixtures/expectedEqualsPlainFormatForJson.txt"
        );
        $expectedDataYml = file_get_contents(
            dirname(__DIR__) . "/Tests/fixtures/expectedEqualsPlainFormatForYml.txt"
        );

        return [
            [$expectedDataJson, $expectedDataYml],
        ];
    }

    public function additionProviderJsonFormat(): array
    {
        $expectedDataJson = file_get_contents(
            dirname(__DIR__) . "/Tests/fixtures/expectedEqualsJsonFormatForJson.json"
        );
        $expectedDataYml = file_get_contents(
            dirname(__DIR__) . "/Tests/fixtures/expectedEqualsJsonFormatForYml.json"
        );

        return [
            [$expectedDataJson, $expectedDataYml],
        ];
    }
}
