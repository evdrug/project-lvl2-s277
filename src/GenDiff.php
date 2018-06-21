<?php

namespace Differ;

use function Differ\OpenFile\openFile;
use function Differ\Parser\parseToFormat;
use function Differ\Reports\reportToFormat;

function getKeys($data1, $data2)
{
    $merge = array_merge(array_keys($data1), array_keys($data2));
    $unique = array_unique($merge);
    return array_values($unique);
}

function normalizeBool($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}

function genDiff($file1, $file2, $format = "pretty")
{
    $formatFile1 = pathinfo($file1, PATHINFO_EXTENSION);
    $formatFile2 = pathinfo($file2, PATHINFO_EXTENSION);
    $data1 = parseToFormat($file1, $formatFile1);
    $data2 = parseToFormat($file2, $formatFile2);

    return reportToFormat(genDiffAst($data1, $data2), $format);
}

function genAstElement($key, $dataBefore, $dataAfter, $action, $children = null)
{
    return [
        'property' => $key,
        'before' => $dataBefore,
        'after' => $dataAfter,
        'action' => $action,
        'children' => $children
    ];
}

function childList($children)
{
    return array_map(function ($key) use ($children) {
        return ['key' => $key, 'value' => $children[$key], 'type' => 'child'];
    }, array_keys($children));
}

function genDiffAst($data1, $data2)
{
    $genDiff = function ($data1, $data2) use (&$genDiff) {
        $keys = getKeys($data1, $data2);
        $result = array_reduce($keys, function ($acc, $key) use ($data1, $data2, $genDiff) {
            $value1 = isset($data1[$key]) ? normalizeBool($data1[$key]) : null;
            $value2 = isset($data2[$key]) ? normalizeBool($data2[$key]) : null;
            if (array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
                if (is_array($value1) && is_array($value2)) {
                    $acc[] = genAstElement($key, null, null, 'nested', $genDiff($value1, $value2));
                } elseif ($value1 === $value2) {
                    $acc[] = genAstElement($key, $value1, $value2, 'not changed');
                } else {
                    $acc[] = genAstElement($key, $value1, $value2, 'changed');
                }
            } elseif (array_key_exists($key, $data1)) {
                if (is_array($value1)) {
                    $acc[] = genAstElement($key, $value1, $value2, 'removed', childList($value1));
                } else {
                    $acc[] = genAstElement($key, $value1, $value2, 'removed');
                }
            } else {
                if (is_array($value2)) {
                    $acc[] = genAstElement($key, null, null, 'added', childList($value2));
                } else {
                    $acc[] = genAstElement($key, null, $value2, 'added');
                }
            }
            return $acc;
        }, []);
        return $result;
    };
    return $genDiff($data1, $data2);
}
