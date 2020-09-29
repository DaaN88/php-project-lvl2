<?php

namespace Gendiff\Application\Functions;

function genDiff(string $pathBeforeFile, string $pathAfterFile)
{
    $dataBefore = parsers($pathBeforeFile);
    $dataAfter = parsers($pathAfterFile);

    $results = getComparedNodes($dataBefore, $dataAfter);

    return "{\n" . implode("\n", $results) . "\n}";
}

function getComparedNodes($dataBefore, $dataAfter)
{
    $results[] = array_reduce(
        array_keys($dataBefore),
        static function (
            $carry,
            $keyDataBefore
        ) use (
            $dataBefore,
            $dataAfter
        ) {
            $key = fetchToString($keyDataBefore);
            $valueDataBefore = fetchToString($dataBefore[$keyDataBefore]);

            if (array_key_exists($keyDataBefore, $dataAfter)) {
                $valueDataAfter = fetchToString($dataAfter[$keyDataBefore]);

                if ($valueDataBefore === $valueDataAfter) { // unchanged
                    $carry[] = "   " . "$key: $valueDataAfter";
                }

                if ($valueDataBefore !== $valueDataAfter) { // Changed
                    $carry[] = " - " . "$key: $valueDataBefore";
                    $carry[] = " + " . "$key: $valueDataAfter";
                }
            }

            if (!array_key_exists($keyDataBefore, $dataAfter)) {
                $carry[] = " - " . "$key: $valueDataBefore"; // deleted
            }

            return $carry;
        },
        []
    );

    $addedNodes = array_map(static function ($keyDataAfter) use ($dataBefore, $dataAfter) {
        if (!array_key_exists($keyDataAfter, $dataBefore)) {
            $key = fetchToString($keyDataAfter);
            $valueDataAfter = fetchToString($dataAfter[$keyDataAfter]);

            return " + " . "$key: $valueDataAfter"; // added
        }
        return [];
    }, array_keys($dataAfter));

    $results[] = array_filter($addedNodes);

    return array_merge(...$results);
}

function fetchToString($value): string
{
    if (is_bool($value)) {
        return $value ? "true" : "false";
    }

    return (string)$value;
}
