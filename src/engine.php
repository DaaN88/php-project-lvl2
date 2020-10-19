<?php

namespace Gendiff\Application\Functions\Engine;

use function Gendiff\Application\Functions\PrettyFormatter\getPrettyFormat;
use function Gendiff\Application\Functions\PlainFormatter\getPlainFormat;
use function Gendiff\Application\Functions\JsonFormatter\getJsonFormat;
use function Gendiff\Application\Functions\Parser\parsers;
use function Gendiff\Application\Functions\buildAst;

function genDiff(string $pathBeforeFile, string $pathAfterFile, string $format): string
{
    $dataBefore = parsers($pathBeforeFile);
    $dataAfter = parsers($pathAfterFile);

    $ast = buildAst($dataBefore, $dataAfter);

    return choiceFormatter($format, $ast);
}

function choiceFormatter(string $format, $ast)
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
