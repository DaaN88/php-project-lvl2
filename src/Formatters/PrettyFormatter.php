<?php

namespace Gendiff\Formatters\PrettyFormatter;

use InvalidArgumentException;

function getPrettyFormat(array $ast): string
{
    $result = getLines($ast, '');

    $result = array_merge(["{"], $result, ["}"]);

    return implode("\n", $result);
}

function getLines(array $ast, string $indent): array
{
    return array_reduce(
        array_keys($ast),
        static function (
            $lines,
            $key
        ) use (
            $ast,
            $indent
        ) {
            $status = $ast[$key]['status'];

            switch ($status) {
                case 'added':
                    $value = valueToString($ast[$key]['value'], $indent);

                    $lines[] = "{$indent}  + {$key}: {$value}";
                    return $lines;
                case 'deleted':
                    $value = valueToString($ast[$key]['value'], $indent);

                    $lines[] = "{$indent}  - {$key}: {$value}";
                    return $lines;
                case 'children':
                    $lines[] = "{$indent}    {$key}: {";

                    $newIndent = "{$indent}    ";

                    $nestedLines = getLines(
                        $ast[$key]['children'],
                        $newIndent
                    );

                    $lines = array_merge($lines, $nestedLines);

                    $lines[] = "{$indent}    }";
                    return $lines;
                case 'unchanged':
                    $value = valueToString($ast[$key]['value'], $indent);

                    $lines[] = "{$indent}    {$key}: {$value}";
                    return $lines;
                case 'changed':
                    $oldValue = valueToString($ast[$key]['oldValue'], $indent);
                    $newValue = valueToString($ast[$key]['newValue'], $indent);

                    $lines[] = "{$indent}  - {$key}: $oldValue";

                    $lines[] = "{$indent}  + {$key}: $newValue";
                    return $lines;
                default:
                    throw new InvalidArgumentException("Unknown status: {$status}. Terminated.");
            }
        },
        []
    );
}

/**
 * @param mixed $value
 * @param string $indent
 *
 * @return string
 */
function valueToString($value, string $indent): string
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

    $lines = array_reduce(
        array_keys($array),
        static function (
            $lines,
            $key
        ) use (
            $array,
            $indent
        ) {
            $value = valueToString($array[$key], $indent);

            $lines[] = "    {$indent}{$key}: $value";

            return $lines;
        },
        []
    );

    $result[] = "{";
    $result[] = implode("\n", $lines);
    $result[] = $indent . "}";

    return implode("\n", $result);
}
