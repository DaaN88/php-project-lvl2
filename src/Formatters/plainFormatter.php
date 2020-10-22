<?php

namespace Gendiff\Formatters\PlainFormatter;

function getPlainFormat($value): string
{
    $iter = static function (
        $currentValues,
        $currentKey
    ) use (&$iter) {
        return array_reduce(
            array_keys($currentValues),
            static function (
                $accum,
                $keyOfValue
            ) use (
                $iter,
                $currentValues,
                $currentKey
            ) {
                $buffer = [];

                if (array_key_exists('status', $currentValues[$keyOfValue])) {
                    $status = $currentValues[$keyOfValue]['status'];

                    switch ($status) {
                        case 'added':
                            $buffer[] = "Property '"
                                .
                                $currentKey
                                .
                                $keyOfValue
                                .
                                "' was added with value: '"
                                .
                                valueToString($currentValues[$keyOfValue]['value'])
                                .
                                "'";
                            break;
                        case 'deleted':
                            $buffer[] = "Property '"
                                .
                                $currentKey
                                .
                                $keyOfValue
                                .
                                "' was removed";
                            break;
                        case 'nested':
                            $currentKey .= $keyOfValue . ".";

                            $goInDepth = $iter(
                                $currentValues[$keyOfValue]['nested structure'],
                                $currentKey
                            );

                            $buffer = array_merge($buffer, $goInDepth);
                            break;
                        case 'changed':
                            $buffer[] = "Property '"
                                .
                                $currentKey
                                .
                                $keyOfValue
                                .
                                "' was updated. From '"
                                .
                                valueToString($currentValues[$keyOfValue]['oldValue'])
                                .
                                "' to '"
                                .
                                valueToString($currentValues[$keyOfValue]['newValue'])
                                .
                                "'";
                            break;
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
