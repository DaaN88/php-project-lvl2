<?php

namespace Gendiff\Formatters\PrettyFormatter;

use InvalidArgumentException;

function getPrettyFormat($value): string
{
    $indent = getIndents(0);

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
                                "  + "
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
                                "  - "
                                .
                                $keyOfValue
                                .
                                ": "
                                .
                                valueToString($currentValues[$keyOfValue]['value'], $indent);
                            break;
                        case 'nested':
                            $buffer[] = $indent . "    " . $keyOfValue . ": {";

                            $newIndent = $indent . getIndents($depth + 2);

                            $goInDepth = $iteratingOverArrays(
                                $currentValues[$keyOfValue]['nested structure'],
                                $newIndent,
                                $depth
                            );

                            $buffer = array_merge($buffer, $goInDepth);

                            $buffer[] = $indent . "    " . "}";
                            break;
                        case 'unchanged':
                            $buffer[] = $indent
                                .
                                "    "
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
                                "  - "
                                .
                                $keyOfValue
                                .
                                ": "
                                .
                                valueToString($currentValues[$keyOfValue]['oldValue'], $indent);

                            $buffer[] = $indent
                                .
                                "  + "
                                .
                                $keyOfValue
                                .
                                ": "
                                .
                                valueToString($currentValues[$keyOfValue]['newValue'], $indent);
                            break;
                        default:
                            throw new InvalidArgumentException("Unknown status: {$status}. Terminated.");
                    }
                }

                return array_merge($accum, $buffer);
            },
            []
        );
    };

    $result = $iteratingOverArrays($value, $indent, 0);

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

function arrayToString(array $array, $indent): string
{
    $indent .= '    ';

    $strings = array_reduce(
        array_keys($array),
        static function (
            $accum,
            $key
        ) use (
            $array,
            $indent
        ) {
            $value = valueToString($array[$key], $indent);

            $buffer[] = $indent . $key . ": " . $value;

            return array_merge($accum, $buffer);
        },
        []
    );

    $result[] = "{";
    $result[] = implode("\n", array_map(static fn($line) => "    $line", $strings));
    $result[] = $indent . "}";

    return implode("\n", $result);
}

function getIndents(int $depth): string
{
    return str_repeat(' ', 2 * $depth);
}
