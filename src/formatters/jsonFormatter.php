<?php

namespace Gendiff\Application\Functions\JsonFormatter;

function getJsonFormat(array $astTree): string
{
    return json_encode($astTree, JSON_THROW_ON_ERROR);
}
