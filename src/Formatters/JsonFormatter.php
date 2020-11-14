<?php

namespace Gendiff\Formatters\JsonFormatter;

function getJsonFormat(array $ast): string
{
    return json_encode($ast, JSON_THROW_ON_ERROR);
}
