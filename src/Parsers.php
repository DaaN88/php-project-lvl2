<?php

namespace Gendiff\Parsers;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

function parse(string $pathFile): array
{
    $fileExtension = pathinfo($pathFile, PATHINFO_EXTENSION);

    switch ($fileExtension) {
        case 'json':
            $data = json_decode(readFile($pathFile), true);
            break;
        case 'yaml':
        case 'yml':
            $data = Yaml::parseFile($pathFile);
            break;
        default:
            throw new InvalidArgumentException("Invalid file extension: {$fileExtension}. Terminated.");
    }

    return $data;
}

function readFile(string $filePath): string
{
    if (!file_exists($filePath)) {
        throw new InvalidArgumentException('File (one or more) doesn\'t exist');
    }

    return file_get_contents($filePath);
}
