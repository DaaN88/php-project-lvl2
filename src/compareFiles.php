<?php

namespace Gendiff\Application\Functions;

use RuntimeException;

function genDiff(string $pathBeforeFile, string $pathAfterFile)
{
    try {
        $dataBefore = json_decode(getData($pathBeforeFile), true);
        $dataAfter = json_decode(getData($pathAfterFile), true);

        $results = getComparedNodes($dataBefore, $dataAfter);

        return "{\n" . implode("\n", $results) . "\n}";
    } catch (RuntimeException $msg) {
        echo $msg->getMessage();
    }
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
            $valueDataBefore = fetchToString($dataBefore[$keyDataBefore]);

            if (array_key_exists($keyDataBefore, $dataAfter)) {
                $valueDataAfter = fetchToString($dataAfter[$keyDataBefore]);

                if ($valueDataBefore === $valueDataAfter) { // unchanged
                    $carry[] = "   " . "$keyDataBefore: $valueDataAfter";
                }

                if ($valueDataBefore !== $valueDataAfter) { // changed
                    $carry[] = " - " . "$keyDataBefore: $valueDataBefore";
                    $carry[] = " + " . "$keyDataBefore: $valueDataAfter";
                }
            }

            if (!array_key_exists($keyDataBefore, $dataAfter)) {
                $carry[] = " - " . "$keyDataBefore: $valueDataBefore"; // deleted
            }

            return $carry;
        },
        []
    );

    $addedNodes = array_map(static function ($keyDataAfter) use ($dataBefore, $dataAfter) {
        if (!array_key_exists($keyDataAfter, $dataBefore)) {
            $valueDataAfter = fetchToString($dataAfter[$keyDataAfter]);

            return " + " . "$keyDataAfter: $valueDataAfter"; // added
        }
        return [];
    }, array_keys($dataAfter));

    $results[] = array_filter($addedNodes);

    return array_merge(...$results);
}

function getData(string $filePath): string
{
    if (!file_exists($filePath)) {
        throw new RuntimeException('File (one or more) doesn\'t exist');
    }

    return file_get_contents($filePath);
}

function fetchToString($value): string
{
    if (is_bool($value)) {
        return $value ? "true" : "false";
    }

    return (string)$value;
}
