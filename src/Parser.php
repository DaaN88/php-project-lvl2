<?php

namespace Gendiff\Parser;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

use function Gendiff\ReadFile\readFile;

function parse(string $filePath): array
{
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

    switch ($fileExtension) {
        case 'json':
            $data = json_decode(readFile($filePath), true, 512, JSON_THROW_ON_ERROR);
            break;
        case 'yaml':
        case 'yml':
            $data = Yaml::parseFile($filePath);
            break;
        default:
            throw new InvalidArgumentException("Invalid file extension: {$fileExtension}. Terminated.");
    }

    return $data;
}
