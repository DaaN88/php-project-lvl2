<?php

namespace Gendiff\ReadFile;

use Exception;
use InvalidArgumentException;

function readFile(string $filePath): string
{
    if (!file_exists($filePath)) {
        throw new InvalidArgumentException("File: {$filePath} doesn\'t exist");
    }

    $dataFile = file_get_contents($filePath);

    if ($dataFile === false) {
        throw new Exception("Can't read file: {$dataFile}! Terminated");
    }

    return $dataFile;
}
