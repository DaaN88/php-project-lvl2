<?php

namespace Gendiff\Formatters\PlainFormatter;

function getPlainFormat($value): string
{
    $iter = static function (
        $currentValues,
        $currentPath
    ) use (&$iter) {
        return array_reduce(
            array_keys($currentValues),
            static function (
                $accum,
                $path
            ) use (
                $iter,
                $currentValues,
                $currentPath
            ) {
                $buffer = [];

                if (array_key_exists('status', $currentValues[$path])) {
                    $status = $currentValues[$path]['status'];

                    switch ($status) {
                        case 'added':
                            $buffer[] = "Property '"
                                .
                                $currentPath
                                .
                                $path
                                .
                                "' was added with value: '"
                                .
                                valueToString($currentValues[$path]['value'])
                                .
                                "'";
                            break;
                        case 'deleted':
                            $buffer[] = "Property '"
                                .
                                $currentPath
                                .
                                $path
                                .
                                "' was removed";
                            break;
                        case 'nested':
                            $currentPath .= $path . ".";

                            $goInDepth = $iter(
                                $currentValues[$path]['nested structure'],
                                $currentPath
                            );

                            $buffer = array_merge($buffer, $goInDepth);
                            break;
                        case 'changed':
                            $buffer[] = "Property '"
                                .
                                $currentPath
                                .
                                $path
                                .
                                "' was updated. From '"
                                .
                                valueToString($currentValues[$path]['oldValue'])
                                .
                                "' to '"
                                .
                                valueToString($currentValues[$path]['newValue'])
                                .
                                "'";
                            break;
                        case 'unchanged':
                            break;
                        default:
                            throw new \InvalidArgumentException("Unknown status: {$status}. Terminated.");
                    }
                }

                return array_merge($accum, $buffer);
            },
            []
        );
    };

    $result = $iter($value, '');

    return implode("\n", $result);
}

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
