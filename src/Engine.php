<?php

namespace Gendiff\Engine;

use Exception;
use InvalidArgumentException;

use function Gendiff\Formatters\PrettyFormatter\getPrettyFormat;
use function Gendiff\Formatters\PlainFormatter\getPlainFormat;
use function Gendiff\Formatters\JsonFormatter\getJsonFormat;
use function Gendiff\Parser\parse;
use function Gendiff\ASTBuilder\buildAst;

function genDiff(string $firstFile, string $secondFile, string $format): string
{
    [$extensionFirst, $dataFirst] = fileReader($firstFile);
    [$extensionSecond, $dataSecond] = fileReader($secondFile);

    $parsedDataBefore = parse($extensionFirst, $dataFirst);
    $parsedDataAfter = parse($extensionSecond, $dataSecond);

    $ast = buildAst($parsedDataBefore, $parsedDataAfter);

    return render($format, $ast);
}

function render(string $format, array $ast): string
{
    switch ($format) {
        case 'plain':
            return getPlainFormat($ast);
        case 'json':
            return getJsonFormat($ast);
        case 'pretty':
            return getPrettyFormat($ast);
        default:
            throw new InvalidArgumentException("Wrong format: {$format}. Terminated.");
    }
}

function fileReader(string $filePath): array
{
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);

    if (!file_exists($filePath)) {
        throw new InvalidArgumentException("File: {$filePath} doesn't exist");
    }

    $data = file_get_contents($filePath);

    if ($data === false) {
        throw new Exception("Can't read file: {$filePath}! Terminated");
    }

    return [$extension, $data];
}
