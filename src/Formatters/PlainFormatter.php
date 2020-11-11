<?php

namespace Gendiff\Formatters\PlainFormatter;

function getPlainFormat(array $ast): string
{
    $result = getLines($ast, '');

    return implode("\n", $result);
}

function getLines(array $ast, string $path): array
{
    return array_reduce(
        array_keys($ast),
        static function (
            $lines,
            $key
        ) use (
            $ast,
            $path
        ) {
            $status = $ast[$key]['status'];

            switch ($status) {
                case 'added':
                    $value = valueToString($ast[$key]['value']);

                    $lines[] = "Property '{$path}{$key}' was added with value: '{$value}'";
                    return $lines;
                case 'deleted':
                    $lines[] = "Property '{$path}{$key}' was removed";
                    return $lines;
                case 'children':
                    $path .= "{$key}.";

                    $nestedLines = getLines(
                        $ast[$key]['children'],
                        $path
                    );

                    $lines = array_merge($lines, $nestedLines);
                    return $lines;
                case 'changed':
                    $oldValue = valueToString($ast[$key]['oldValue']);
                    $newValue = valueToString($ast[$key]['newValue']);

                    $lines[] = "Property '{$path}{$key}' was updated. From '{$oldValue}' to '{$newValue}'";
                    return $lines;
                case 'unchanged':
                    return $lines;
                default:
                    throw new \Exception("Unknown status: {$status}. Terminated.");
            }
        },
        []
    );
}

/**
 * @param mixed $value
 *
 * @return string
 */
function valueToString($value): string
{
    if (is_array($value)) {
        return "[complex value]";
    }

    if (is_bool($value)) {
        return $value ? "true" : "false";
    }

    return (string)$value;
}
