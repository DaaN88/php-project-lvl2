<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Engine\genDiff;

class GenDiffTest extends TestCase
{
    private function getFilePath($file): string
    {
        return __DIR__ . "/fixtures/{$file}";
    }

    /**
     * @dataProvider additionProviderFormat
     *
     * @param string $expectedData path
     * @param string $prevVerFile path
     * @param string $newVerFile path
     * @param string $format
     *
     */
    public function testEqualsFormat(
        string $pathExpectedFile,
        string $firstFile,
        string $secondFile,
        string $format
    ): void {
        $expected = file_get_contents($this->getFilePath($pathExpectedFile));

        self::assertEquals(
            $expected,
            genDiff(
                $this->getFilePath($firstFile),
                $this->getFilePath($secondFile),
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
