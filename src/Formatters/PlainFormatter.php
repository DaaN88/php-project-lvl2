<?php

namespace Gendiff\Formatters\PlainFormatter;

function getPlainFormat(array $treeAST): string
{
    $iter = static function (
        $valuesOfTree,
        $currentKey
    ) use (&$iter) {
        return array_reduce(
            array_keys($valuesOfTree),
            static function (
                $lines,
                $key
            ) use (
                $iter,
                $valuesOfTree,
                $currentKey
            ) {
                $status = $valuesOfTree[$key]['status'];

                switch ($status) {
                    case 'added':
                        $value = valueToString($valuesOfTree[$key]['value']);

                        $lines[] = "Property '{$currentKey}{$key}' was added with value: '{$value}'";
                        break;
                    case 'deleted':
                        $lines[] = "Property '{$currentKey}{$key}' was removed";
                        break;
                    case 'children':
                        $currentKey .= "{$key}.";

                        $goInDepth = $iter(
                            $valuesOfTree[$key]['children'],
                            $currentKey
                        );

                        $lines = array_merge($lines, $goInDepth);
                        break;
                    case 'changed':
                        $oldValue = valueToString($valuesOfTree[$key]['oldValue']);
                        $newValue = valueToString($valuesOfTree[$key]['newValue']);

                        $lines[] = "Property '{$currentKey}{$key}' was updated. From '{$oldValue}' to '{$newValue}'";
                        break;
                    case 'unchanged':
                        break;
                    default:
                        throw new \Exception("Unknown status: {$status}. Terminated.");
                }

                return $lines;
            },
            []
        );
    };

    $result = $iter($treeAST, '');

    return implode("\n", $result);
}

function valueToString($value): string //$value - type mixed
{
    if (is_array($value)) {
        return "[complex value]";
    }

    if (is_bool($value)) {
        return $value ? "true" : "false";
    }

    return (string)$value;
}
