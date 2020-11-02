<?php

namespace Gendiff\Parsers;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

function parse(string $filePath): array
{
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

    switch ($fileExtension) {
        case 'json':
            $data = json_decode(readFile($filePath), true, 512,JSON_THROW_ON_ERROR);
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

function readFile(string $filePath): string
{
    if (!file_exists($filePath)) {
        throw new InvalidArgumentException('File (one or more) doesn\'t exist');
    }

    if (!file_get_contents($filePath)) {
        throw new Exception("Can't read file! Terminated");
    }

    return file_get_contents($filePath);
}
