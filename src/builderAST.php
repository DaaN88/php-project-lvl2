<?php

namespace Gendiff\Application\Functions;

use function Funct\Collection\union;

function buildAst(array $dataBefore, array $dataAfter): array
{
    $iteratingOverArrays = static function (
        $dataBeforeValues,
        $dataAfterValues
    ) use (&$iteratingOverArrays) {
        return array_reduce(
            union(array_keys($dataBeforeValues), array_keys($dataAfterValues)),
            static function (
                $carry,
                $sharedKey //общий ключ (и из старого и из нового массивов)
            ) use (
                $dataBeforeValues,
                $dataAfterValues,
                $iteratingOverArrays
            ) {
                $buffer = [];

                // если ключа нет во втором
                if (!array_key_exists($sharedKey, $dataAfterValues)) {
                    $oldValue = $dataBeforeValues[$sharedKey];

                    $buffer[$sharedKey] = ['value' => $oldValue, 'status' => 'deleted'];

                    return array_merge($carry, $buffer);
                }

                // если ключа нет в первом
                if (!array_key_exists($sharedKey, $dataBeforeValues)) {
                    $newValue = $dataAfterValues[$sharedKey];

                    $buffer[$sharedKey] = ['value' => $newValue, 'status' => 'added'];

                    return array_merge($carry, $buffer);
                }

                $oldValue = $dataBeforeValues[$sharedKey];
                $newValue = $dataAfterValues[$sharedKey];

                if ($oldValue === $newValue) {
                    $buffer[$sharedKey] = ['value' => $oldValue, 'status' => 'unchanged'];
                } elseif (is_array($oldValue) && is_array($newValue)) {
                    $goInDepth = $iteratingOverArrays(
                        $oldValue,
                        $newValue
                    );

                    $buffer[$sharedKey] = ['nested structure' => $goInDepth, 'status' => 'nested'];
                } else {
                    $buffer[$sharedKey] = [
                        'oldValue' => $oldValue,
                        'newValue' => $newValue,
                        'status' => 'changed',
                    ];
                }

                return array_merge($carry, $buffer);
            },
            []
        );
    };

    return $iteratingOverArrays($dataBefore, $dataAfter);
}
