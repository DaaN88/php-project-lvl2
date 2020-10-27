<?php

namespace Gendiff\BuilderAST;

use function Funct\Collection\union;

function buildAst(array $dataBefore, array $dataAfter): array
{
    return array_reduce(
        union(array_keys($dataBefore), array_keys($dataAfter)),
        static function (
            $carry,
            $sharedKey
        ) use (
            $dataBefore,
            $dataAfter
        ) {
            $buffer = [];

            if (!array_key_exists($sharedKey, $dataAfter)) {
                $oldValue = $dataBefore[$sharedKey];

                $buffer[$sharedKey] = ['value' => $oldValue, 'status' => 'deleted'];

                return array_merge($carry, $buffer);
            }

            if (!array_key_exists($sharedKey, $dataBefore)) {
                $newValue = $dataAfter[$sharedKey];

                $buffer[$sharedKey] = ['value' => $newValue, 'status' => 'added'];

                return array_merge($carry, $buffer);
            }

            $oldValue = $dataBefore[$sharedKey];
            $newValue = $dataAfter[$sharedKey];

            if ($oldValue === $newValue) {
                $buffer[$sharedKey] = ['value' => $oldValue, 'status' => 'unchanged'];

                return array_merge($carry, $buffer);
            }

            if (is_array($oldValue) && is_array($newValue)) {
                $goInDepth = buildAst(
                    $oldValue,
                    $newValue
                );

                $buffer[$sharedKey] = ['nested structure' => $goInDepth, 'status' => 'nested'];

                return array_merge($carry, $buffer);
            }

            $buffer[$sharedKey] = [
                'oldValue' => $oldValue,
                'newValue' => $newValue,
                'status' => 'changed',
            ];

            return array_merge($carry, $buffer);
        },
        []
    );
}
