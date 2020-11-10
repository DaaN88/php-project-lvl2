<?php

namespace Gendiff\Engine;

use function Gendiff\Formatters\PrettyFormatter\getPrettyFormat;
use function Gendiff\Formatters\PlainFormatter\getPlainFormat;
use function Gendiff\Formatters\JsonFormatter\getJsonFormat;
use function Gendiff\Parser\parse;
use function Gendiff\ASTBuilder\buildAst;

function genDiff(string $filePrevVerPath, string $fileNewVerPath, string $format): string
{
    $dataBefore = parse($filePrevVerPath);
    $dataAfter = parse($fileNewVerPath);

    $ast = buildAst($dataBefore, $dataAfter);

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
