<?php

namespace Gendiff\Formatters\PrettyFormatter;

use InvalidArgumentException;

function getPrettyFormat(array $treeAST): string
{
    $iteratingOverArrays = static function (
        $currentValues,
        $indent
    ) use (&$iteratingOverArrays) {
        return array_reduce(
            array_keys($currentValues),
            static function (
                $accum,
                $key
            ) use (
                $iteratingOverArrays,
                $currentValues,
                $indent
            ) {
                $buffer = [];

                if (array_key_exists('status', $currentValues[$key])) {
                    $status = $currentValues[$key]['status'];

                    switch ($status) {
                        case 'added':
                            $buffer[] = $indent
                                .
                                "  + "
                                .
                                $key
                                .
                                ": "
                                .
                                valueToString($currentValues[$key]['value'], $indent);
                            break;
                        case 'deleted':
                            $buffer[] = $indent
                                .
                                "  - "
                                .
                                $key
                                .
                                ": "
                                .
                                valueToString($currentValues[$key]['value'], $indent);
                            break;
                        case 'nested':
                            $buffer[] = $indent . "    " . $key . ": {";

                            $newIndent = $indent . '    ';

                            $goInDepth = $iteratingOverArrays(
                                $currentValues[$key]['nested structure'],
                                $newIndent
                            );

                            $buffer = array_merge($buffer, $goInDepth);

                            $buffer[] = $indent . "    " . "}";
                            break;
                        case 'unchanged':
                            $buffer[] = $indent
                                .
                                "    "
                                .
                                $key
                                .
                                ": "
                                .
                                valueToString($currentValues[$key]['value'], $indent);
                            break;
                        case 'changed':
                            $buffer[] = $indent
                                .
                                "  - "
                                .
                                $key
                                .
                                ": "
                                .
                                valueToString($currentValues[$key]['oldValue'], $indent);

                            $buffer[] = $indent
                                .
                                "  + "
                                .
                                $key
                                .
                                ": "
                                .
                                valueToString($currentValues[$key]['newValue'], $indent);
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

    $result = $iteratingOverArrays($treeAST, '');

    $result = array_merge(["{"], $result, ["}"]);

    return implode("\n", $result);
}

function valueToString($value, string $indent): string //$value - type mixed
{
    if (is_array($value)) {
        return arrayToString($value, $indent);
    }

    if (is_bool($value)) {
        return $value ? "true" : "false";
    }

    return (string)$value;
}

function arrayToString(array $array, string $indent): string
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

            $accum[] = $indent . $key . ": " . $value;

            return $accum;
        },
        []
    );

    $result[] = "{";
    $result[] = implode("\n", array_map(static fn($line) => "    $line", $strings));
    $result[] = $indent . "}";

    return implode("\n", $result);
}
