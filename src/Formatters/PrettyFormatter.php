<?php

namespace Gendiff\Formatters\PrettyFormatter;

use InvalidArgumentException;

function getPrettyFormat(array $ast): string
{
    $result = iteratingOverArrays($ast, '');

    $result = array_merge(["{"], $result, ["}"]);

    return implode("\n", $result);
}

function iteratingOverArrays($ast, $indent)
{
    return array_reduce(
        array_keys($ast),
        static function (
            $accum,
            $key
        ) use (
            $ast,
            $indent
        ) {
            $status = $ast[$key]['status'];

            switch ($status) {
                case 'added':
                    $value = valueToString($ast[$key]['value'], $indent);

                    $accum[] = "{$indent}  + {$key}: {$value}";
                    break;
                case 'deleted':
                    $value = valueToString($ast[$key]['value'], $indent);

                    $accum[] = "{$indent}  - {$key}: {$value}";
                    break;
                case 'children':
                    $accum[] = "{$indent}    {$key}: {";

                    $newIndent = "{$indent}    ";

                    $goInDepth = iteratingOverArrays(
                        $ast[$key]['children'],
                        $newIndent
                    );

                    $accum = array_merge($accum, $goInDepth);

                    $accum[] = "{$indent}    }";
                    break;
                case 'unchanged':
                    $value = valueToString($ast[$key]['value'], $indent);

                    $accum[] = "{$indent}    {$key}: {$value}";
                    break;
                case 'changed':
                    $oldValue = valueToString($ast[$key]['oldValue'], $indent);
                    $newValue = valueToString($ast[$key]['newValue'], $indent);

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
