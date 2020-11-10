<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Engine\genDiff;
use function Gendiff\ReadFile\readFile;

class GenDiffTest extends TestCase
{
    private function getFilePath($file): string
    {
        return dirname(__DIR__) . "/tests/fixtures/{$file}";
    }
    /**
     * @dataProvider additionProviderFormat
     *
     * @param $fileWithExpectedData
     * @param $prevVerFile
     * @param $newVerFile
     * @param $format
     *
     * @throws \Exception
     */
    public function testEqualsFormat($fileWithExpectedData, $prevVerFile, $newVerFile, string $format): void
    {
        $expected = readFile($this->getFilePath($fileWithExpectedData));

        self::assertEquals(
            $expected,
            genDiff(
                $this->getFilePath($prevVerFile),
                $this->getFilePath($newVerFile),
                $format
            )
        );
    }

    public function additionProviderFormat(): array
    {
        return [
            'JsonPrettyFormat' => [
                'expectedPrettyFormat.txt',
                'testFileNestedOne.json',
                'testFileNestedTwo.json',
                'pretty'
            ],
            'JsonPlainFormat' => [
                'expectedPlainFormat.txt',
                'testFileNestedOne.json',
                'testFileNestedTwo.json',
                'plain'
            ],
            'JsonJsonFormat' => [
                'expectedJsonFormat.json',
                'testFileNestedOne.json',
                'testFileNestedTwo.json',
                'json'
            ],
            'YmlPrettyFormat' => [
                'expectedPrettyFormat.txt',
                'testFileNestedOne.yml',
                'testFileNestedTwo.yml',
                'pretty'
            ],
            'YmlPlainFormat' => [
                'expectedPlainFormat.txt',
                'testFileNestedOne.yml',
                'testFileNestedTwo.yml',
                'plain'
            ],
            'YmlJsonFormat' => [
                'expectedJsonFormat.json',
                'testFileNestedOne.yml',
                'testFileNestedTwo.yml',
                'json'
            ],
        ];
    }
}
