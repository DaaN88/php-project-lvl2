<?php

namespace Gendiff\Tests;

use http\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

use function Gendiff\Engine\genDiff;

class GenDiffTest extends TestCase
{
    private $pathToFileBeforeJson = __DIR__ . '/fixtures/testFileNestedOne.json';
    private $pathToFileAfterJson = __DIR__ . '/fixtures/testFileNestedTwo.json';

    private $pathToFileBeforeYml = __DIR__ . '/fixtures/testFileNestedOne.yml';
    private $pathToFileAfterYml = __DIR__ . '/fixtures/testFileNestedTwo.yml';

    private $pathToFileExpectedJsonFormat = __DIR__ . "/fixtures/expectedJsonFormat.json";
    private $pathToFileExpectedPlainFormat = __DIR__ . "/fixtures/expectedPlainFormat.txt";
    private $pathToFileExpectedPrettyFormat = __DIR__ . "/fixtures/expectedPrettyFormat.txt";

    /**
     * @dataProvider additionProviderFormat
     *
     * @param $expectedData
     * @param $dataBefore
     * @param $dataAfter
     * @param $format
     */
    public function testEqualsFormat($expectedData, $dataBefore, $dataAfter, $format): void
    {
        self::assertEquals($expectedData, genDiff($dataBefore, $dataAfter, $format));
    }

    public function additionProviderFormat(): array
    {
        $expectedJsonFormat = file_get_contents(
            $this->pathToFileExpectedJsonFormat
        );

        $expectedPlainFormat = file_get_contents(
            $this->pathToFileExpectedPlainFormat
        );

        $expectedPrettyFormat = file_get_contents(
            $this->pathToFileExpectedPrettyFormat
        );

        return [
            'JsonPrettyFormat' => [
                $expectedPrettyFormat,
                $this->pathToFileBeforeJson,
                $this->pathToFileAfterJson,
                'pretty'
            ],
            'JsonPlainFormat' => [
                $expectedPlainFormat,
                $this->pathToFileBeforeJson,
                $this->pathToFileAfterJson,
                'plain'
            ],
            'JsonJsonFormat' => [
                $expectedJsonFormat,
                $this->pathToFileBeforeJson,
                $this->pathToFileAfterJson,
                'json'
            ],
            'YmlPrettyFormat' => [
                $expectedPrettyFormat,
                $this->pathToFileBeforeYml,
                $this->pathToFileAfterYml,
                'pretty'
            ],
            'YmlPlainFormat' => [
                $expectedPlainFormat,
                $this->pathToFileBeforeYml,
                $this->pathToFileAfterYml,
                'plain'
            ],
            'YmlJsonFormat' => [
                $expectedJsonFormat,
                $this->pathToFileBeforeYml,
                $this->pathToFileAfterYml,
                'json'
            ],
        ];
    }
}
