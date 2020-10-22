<?php

namespace Gendiff\Formatters\PrettyFormatter;

function getPrettyFormat($value, int $depth = 0): string
{
    $indent = getIndents($depth);

    $iteratingOverArrays = static function (
        $currentValues,
        $indent,
        $depth
    ) use (&$iteratingOverArrays) {
        return array_reduce(
            array_keys($currentValues),
            static function (
                $accum,
                $keyOfValue
            ) use (
                $iteratingOverArrays,
                $currentValues,
                $indent,
                $depth
            ) {
                $buffer = [];

                if (array_key_exists('status', $currentValues[$keyOfValue])) {
                    $status = $currentValues[$keyOfValue]['status'];

                    switch ($status) {
                        case 'added':
                            $buffer[] = $indent
                                .
                                " + "
                                .
                                $keyOfValue
                                .
                                ": "
                                .
                                valueToString($currentValues[$keyOfValue]['value'], $indent);
                            break;
                        case 'deleted':
                            $buffer[] = $indent
                                .
                                " - "
                                .
                                $keyOfValue
                                .
                                ": "
                                .
                                valueToString($currentValues[$keyOfValue]['value'], $indent);
                            break;
                        case 'nested':
                            $buffer[] = $indent . "   " . $keyOfValue . ": {";

                            $newIndent = $indent . getIndents($depth + 2);

                            $goInDepth = $iteratingOverArrays(
                                $currentValues[$keyOfValue]['nested structure'],
                                $newIndent,
                                $depth
                            );

                            $buffer = array_merge($buffer, $goInDepth);

                            $buffer[] = $indent . "   " . "}";
                            break;
                        case 'unchanged':
                            $buffer[] = $indent
                                .
                                "   "
                                .
                                $keyOfValue
                                .
                                ": "
                                .
                                valueToString($currentValues[$keyOfValue]['value'], $indent);
                            break;
                        case 'changed':
                            $buffer[] = $indent
                                .
                                " - "
                                .
                                $keyOfValue
                                .
                                ": "
                                .
                                valueToString($currentValues[$keyOfValue]['oldValue'], $indent);

                            $buffer[] = $indent
                                .
                                " + "
                                .
                                $keyOfValue
                                .
                                ": "
                                .
                                valueToString($currentValues[$keyOfValue]['newValue'], $indent);
                            break;
                    }
                }

                return array_merge($accum, $buffer);
            },
            []
        );
    };

    $result = $iteratingOverArrays($value, $indent, $depth);

    $result = array_merge(["{"], $result, ["}"]);

    return implode("\n", $result);
}

function valueToString($value, $indent): string
{
    if (is_array($value)) {
        return arrayToString($value, $indent);
    }

    if (is_bool($value)) {
        return $value ? "true" : "false";
    }

    return (string)$value;
}

function arrayToString($array, $indent)
{
    $iter = static function (
        $currentValues,
        $indent
    ) use (&$iter) {
        return array_reduce(
            array_keys($currentValues),
            static function (
                $accum,
                $keyOfValue
            ) use (
                $iter,
                $currentValues,
                $indent
            ) {
                $newIndent = $indent . getIndents(2);

                if (is_array($currentValues[$keyOfValue])) {
                    $buffer[] = $newIndent . $keyOfValue . ": {";

                    $goInDepth = $iter(
                        $currentValues[$keyOfValue],
                        $newIndent
                    );
                    $buffer = array_merge($buffer, $goInDepth);

                    $buffer[] = $newIndent . "}";
                } else {
                    $valueLikeString = $currentValues[$keyOfValue];

                    $buffer[] = "{$newIndent}{$keyOfValue}: {$valueLikeString}";
                }

                return array_merge($accum, $buffer);
            },
            []
        );
    };

    $result = $iter($array, $indent);

    $result = array_merge(["{"], $result, [$indent . "   " . "}"]);

    return implode("\n", $result);
}

function getIndents(int $depth): string
{
    return str_repeat(' ', 2 * $depth);
}
