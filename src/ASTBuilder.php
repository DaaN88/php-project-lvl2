<?php

namespace Gendiff\ASTBuilder;

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
            if (!array_key_exists($sharedKey, $dataAfter)) {
                $oldValue = $dataBefore[$sharedKey];

                $carry[$sharedKey] = ['value' => $oldValue, 'status' => 'deleted'];
                return $carry;
            }

            if (!array_key_exists($sharedKey, $dataBefore)) {
                $newValue = $dataAfter[$sharedKey];

                $carry[$sharedKey] = ['value' => $newValue, 'status' => 'added'];
                return $carry;
            }

            $oldValue = $dataBefore[$sharedKey];
            $newValue = $dataAfter[$sharedKey];

            if ($oldValue === $newValue) {
                $carry[$sharedKey] = ['value' => $oldValue, 'status' => 'unchanged'];
                return $carry;
            }

            if (is_array($oldValue) && is_array($newValue)) {
                $children = buildAst(
                    $oldValue,
                    $newValue
                );

                $carry[$sharedKey] = ['children' => $children, 'status' => 'children'];
                return $carry;
            }

            $carry[$sharedKey] = [
                'oldValue' => $oldValue,
                'newValue' => $newValue,
                'status' => 'changed',
            ];

            return $carry;
        },
        []
    );
}
