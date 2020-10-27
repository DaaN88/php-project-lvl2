<?php

namespace Gendiff\Engine;

use function Gendiff\Formatters\PrettyFormatter\getPrettyFormat;
use function Gendiff\Formatters\PlainFormatter\getPlainFormat;
use function Gendiff\Formatters\JsonFormatter\getJsonFormat;
use function Gendiff\Parsers\parse;
use function Gendiff\BuilderAST\buildAst;

function genDiff(string $pathBeforeFile, string $pathAfterFile, string $format): string
{
    $dataBefore = parse($pathBeforeFile);
    $dataAfter = parse($pathAfterFile);

    $ast = buildAst($dataBefore, $dataAfter);

    return getStringRepresentation($format, $ast);
}

function getStringRepresentation(string $format, array $ast): string
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
