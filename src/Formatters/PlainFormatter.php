<?php

namespace Gendiff\Formatters\PlainFormatter;

use InvalidArgumentException;

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
                        $lines[] = "Property '"
                          .
                          $currentKey
                          .
                          $key
                          .
                          "' was added with value: '"
                          .
                          valueToString($valuesOfTree[$key]['value'])
                          .
                          "'";
                        break;
                    case 'deleted':
                        $lines[] = "Property '"
                          .
                          $currentKey
                          .
                          $key
                          .
                          "' was removed";
                        break;
                    case 'nested':
                        $currentKey .= $key . ".";

                        $goInDepth = $iter(
                            $valuesOfTree[$key]['nested structure'],
                            $currentKey
                        );

                        $lines = array_merge($lines, $goInDepth);
                        break;
                    case 'changed':
                        $lines[] = "Property '"
                          .
                          $currentKey
                          .
                          $key
                          .
                          "' was updated. From '"
                          .
                          valueToString($valuesOfTree[$key]['oldValue'])
                          .
                          "' to '"
                          .
                          valueToString($valuesOfTree[$key]['newValue'])
                          .
                          "'";
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
