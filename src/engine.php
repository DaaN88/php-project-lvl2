<?php

namespace Gendiff\Engine\genDiff;

use function Gendiff\Formatters\PrettyFormatter\getPrettyFormat;
use function Gendiff\Formatters\PlainFormatter\getPlainFormat;
use function Gendiff\Formatters\JsonFormatter\getJsonFormat;
use function Gendiff\Parser\parsers\parsers;
use function Gendiff\BuilderAST\buildAst\buildAst;

function genDiff(string $pathBeforeFile, string $pathAfterFile, string $format): string
{
    $dataBefore = parsers($pathBeforeFile);
    $dataAfter = parsers($pathAfterFile);

    $ast = buildAst($dataBefore, $dataAfter);

    return choiceFormatter($format, $ast);
}

function choiceFormatter(string $format, array $ast): string
{
    switch ($format) {
        case 'plain':
            return getPlainFormat($ast);
        case 'json':
            return getJsonFormat($ast);
        case 'pretty':
            return getPrettyFormat($ast);
        default:
            throw new \RuntimeException("Wrong format! Terminated.");
    }
}
