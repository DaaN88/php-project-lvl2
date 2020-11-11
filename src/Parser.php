<?php

namespace Gendiff\Parser;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

function parse(string $format, string $data): array
{
    switch ($format) {
        case 'json':
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            break;
        case 'yaml':
        case 'yml':
            $data = Yaml::parse($data);
            break;
        default:
            throw new InvalidArgumentException("Invalid file extension: {$format}. Terminated.");
    }

    return $data;
}
