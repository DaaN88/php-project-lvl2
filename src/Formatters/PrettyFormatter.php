<?php

namespace Gendiff\Formatters\PrettyFormatter;

use InvalidArgumentException;

function getPrettyFormat(array $treeAST): string
{
    $result = iteratingOverArrays($treeAST, '');

    $result = array_merge(["{"], $result, ["}"]);

    return implode("\n", $result);
}

function iteratingOverArrays($currentValues, $indent)
{
    return array_reduce(
        array_keys($currentValues),
        static function (
            $accum,
            $key
        ) use (
            $currentValues,
            $indent
        ) {
            $status = $currentValues[$key]['status'];

            switch ($status) {
                case 'added':
                    $value = valueToString($currentValues[$key]['value'], $indent);

                    $accum[] = "{$indent}  + {$key}: {$value}";
                    break;
                case 'deleted':
                    $value = valueToString($currentValues[$key]['value'], $indent);

                    $accum[] = "{$indent}  - {$key}: {$value}";
                    break;
                case 'children':
                    $accum[] = "{$indent}    {$key}: {";

                    $newIndent = "{$indent}    ";

                    $goInDepth = iteratingOverArrays(
                        $currentValues[$key]['children'],
                        $newIndent
                    );

                    $accum = array_merge($accum, $goInDepth);

                    $accum[] = "{$indent}    }";
                    break;
                case 'unchanged':
                    $value = valueToString($currentValues[$key]['value'], $indent);

                    $accum[] = "{$indent}    {$key}: {$value}";
                    break;
                case 'changed':
                    $oldValue = valueToString($currentValues[$key]['oldValue'], $indent);
                    $newValue = valueToString($currentValues[$key]['newValue'], $indent);

                    $accum[] = "{$indent}  - {$key}: $oldValue";

                    $accum[] = "{$indent}  + {$key}: $newValue";
                    break;
                default:
                    throw new InvalidArgumentException("Unknown status: {$status}. Terminated.");
            }

            return $accum;
        },
        []
    );
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
