<?php

namespace Gendiff\Application\Functions;

function genDiff(string $nameCurrentFile, string $nameNewFile)
{
    $arrayFromCurrentFile = json_decode(file_get_contents($nameCurrentFile), true);
    $arrayFromNewFile = json_decode(file_get_contents($nameNewFile), TRUE);

    $results[] = array_reduce(
        array_keys($arrayFromCurrentFile),
        static function(
            $carry,
            $keyFromCurrentFile
        ) use (
            $arrayFromCurrentFile,
            $arrayFromNewFile
        ) {
            $valueCurrentFile = fetchToString($arrayFromCurrentFile[$keyFromCurrentFile]);

            if (array_key_exists($keyFromCurrentFile, $arrayFromNewFile)) {
                $valueNewFile = fetchToString($arrayFromNewFile[$keyFromCurrentFile]);

                if ($valueCurrentFile === $valueNewFile) { // unchanged
                    $carry[] = "   " . "$keyFromCurrentFile: $valueNewFile";
                }

                if ($valueCurrentFile !== $valueNewFile) { // changed
                    $carry[] =" - " . "$keyFromCurrentFile: $valueCurrentFile";
                    $carry[] =" + " . "$keyFromCurrentFile: $valueNewFile";
                }
            }

            if (!array_key_exists($keyFromCurrentFile, $arrayFromNewFile)) {
                $carry[] = " - " . "$keyFromCurrentFile: $valueCurrentFile"; // deleted
            }

            return $carry;
        },
        []
    );

    $results[] = array_reduce(
        array_keys($arrayFromNewFile),
        static function (
            $carry,
            $keyFromNewFile
        ) use (
            $arrayFromNewFile,
            $arrayFromCurrentFile
        ) {
            $valueNewFile = fetchToString($arrayFromNewFile[$keyFromNewFile]);

            if (!array_key_exists($keyFromNewFile, $arrayFromCurrentFile)) {
                $carry[] = " + " . "$keyFromNewFile: $valueNewFile"; // added
            }

            return $carry;
        },
        []
    );

    $mergedResults = array_merge(...$results);

    return "{\n" . implode("\n", $mergedResults) . "\n}";
}

function fetchToString($value): string
{
    if (is_bool($value)) {
        return $value ? "true" : "false";
    }

    return (string)$value;
}
