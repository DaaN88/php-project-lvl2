<?php

namespace Gendiff\Application\Functions;

use function Funct\Collection\union;

function genDiff($pathBeforeFile, $pathAfterFile)
{
    $dataBefore = parsers($pathBeforeFile);
    $dataAfter = parsers($pathAfterFile);

    return formatAst(buildAst($dataBefore, $dataAfter));
    //print_r(buildAst($dataBefore, $dataAfter));
}

/*function buildAst(array $dataBefore, array $dataAfter): array
{
    $iteratingOverArrays = static function (
        $dataBeforeValues,
        $dataAfterValues
    ) use (&$iteratingOverArrays) {
        return array_reduce(
            array_keys($dataBeforeValues),
            static function (
                $carry,
                $keyDataBefore
            ) use (
                $dataBeforeValues,
                $dataAfterValues,
                $iteratingOverArrays
            ) {
                $buffer = [];
                $oldValue = $dataBeforeValues[$keyDataBefore];

                $keysDataAfter = array_keys($dataAfterValues);

                if (array_key_exists($keyDataBefore, $dataAfterValues)) {
                    $newValue = $dataAfterValues[$keyDataBefore];

                    if ($oldValue === $newValue) {
                        $buffer[$keyDataBefore] = ['value' => $oldValue, 'status' => 'unchanged'];
                    }

                    if ($newValue !== $oldValue) {
                        if (!is_array($oldValue) && !is_array($newValue)) {
                            $buffer[$keyDataBefore] = [
                                'oldValue' => $oldValue,
                                'newValue' => $newValue,
                                'status' => 'changed',
                            ];
                        }

                        if (is_array($oldValue) && !is_array($newValue)) {
                            $key = key($oldValue);

                            $buffer[$keyDataBefore] = [
                                'oldValue' => $oldValue[$key],
                                'newValue' => $newValue,
                                'status' => 'changed',
                            ];
                        }

                        if (!is_array($oldValue) && is_array($newValue)) {
                            $key = key($newValue);

                            $buffer[$keyDataBefore] = [
                                'oldValue' => $oldValue,
                                'newValue' => $newValue[$key],
                                'status' => 'changed',
                            ];
                        }

                        if (is_array($oldValue) && is_array($newValue)) {
                            $goInDepth = $iteratingOverArrays(
                                $oldValue,
                                $newValue
                            );

                            $buffer[$keyDataBefore] = ['nested structure' => $goInDepth, 'status' => 'nested'];
                            //$buffer = array_merge($buffer, $goInDepth);
                        }
                    }
                } else {
                    $buffer[$keyDataBefore] = ['value' => $oldValue, 'status' => 'deleted'];
                }

                $addedValues = array_map(static function ($keyAfter) use ($dataAfterValues, $dataBeforeValues) {
                    $temp = [];

                    if (!array_key_exists($keyAfter, $dataBeforeValues)) {
                        $temp[$keyAfter] = ['value' => $dataAfterValues[$keyAfter], 'status' => 'added'];
                    }

                    return $temp;
                }, $keysDataAfter);

                $buffer = array_merge($buffer, ...array_filter($addedValues));

                return array_merge($carry, $buffer);
            },
            []
        );
    };

    return $iteratingOverArrays($dataBefore, $dataAfter);
}*/

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
