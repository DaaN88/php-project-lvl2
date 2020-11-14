<?php

namespace Gendiff\Parser;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

function parse(string $format, string $data): array
{
    switch ($format) {
        case 'json':
            return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        case 'yaml':
        case 'yml':
            return Yaml::parse($data);
        default:
            throw new InvalidArgumentException("Invalid format: {$format}. Terminated.");
    }
}
