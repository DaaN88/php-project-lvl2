<?php

namespace Gendiff\ReadFile;

use Exception;
use InvalidArgumentException;

function readFile(string $filePath): array
{
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);

    if (!file_exists($filePath)) {
        throw new InvalidArgumentException("File: {$filePath} doesn\'t exist");
    }

    $data = file_get_contents($filePath);

    if ($data === false) {
        throw new Exception("Can't read file: {$data}! Terminated");
    }

    return [$extension, $data];
}
