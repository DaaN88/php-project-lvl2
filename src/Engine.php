<?php

namespace Gendiff\Engine;

use function Gendiff\Formatters\PrettyFormatter\getPrettyFormat;
use function Gendiff\Formatters\PlainFormatter\getPlainFormat;
use function Gendiff\Formatters\JsonFormatter\getJsonFormat;
use function Gendiff\Parser\parse;
use function Gendiff\ReadFile\readFile;
use function Gendiff\ASTBuilder\buildAst;

function genDiff(string $firstFile, string $secondFile, string $format): string
{
    [$extensionFirst, $dataFirst] = readFile($firstFile);
    [$extensionSecond, $dataSecond] = readFile($secondFile);

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
            throw new \InvalidArgumentException("Wrong format: {$format}. Terminated.");
    }
}
