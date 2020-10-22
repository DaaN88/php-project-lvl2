<?php

namespace Gendiff\Formatters\JsonFormatter;

function getJsonFormat(array $astTree): string
{
    return json_encode($astTree, JSON_THROW_ON_ERROR);
}
