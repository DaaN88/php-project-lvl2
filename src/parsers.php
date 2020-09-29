<?php

namespace Gendiff\Application\Functions;

use RuntimeException;
use Symfony\Component\Yaml\Yaml;

function parsers(string $pathFile): array
{
    $info = getFileExtension($pathFile);
    $data = [];

    try {
        if ($info === 'json') {
            $data = json_decode(getData($pathFile), true);
        }

        if ($info === 'yml') {
            $data = Yaml::parseFile($pathFile, Yaml::PARSE_OBJECT_FOR_MAP);
        }
    } catch (RuntimeException $msg) {
        echo $msg->getMessage();
        exit();
    }

    return (array)$data; // purposeful casting: in case of yaml files
}

function getFileExtension(string $pathFile): string
{
    $info = pathinfo($pathFile);

    return $info['extension'];
}

function getData(string $filePath): string
{
    if (!file_exists($filePath)) {
        throw new RuntimeException('File (one or more) doesn\'t exist');
    }

    return file_get_contents($filePath);
}
