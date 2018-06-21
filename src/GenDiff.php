<?php

namespace Differ;

use function Differ\Parser\parseToFormat;
use function Differ\Reports\reportToFormat;

function getKeys($data1, $data2)
{
    $merge = array_merge(array_keys($data1), array_keys($data2));
    $unique = array_unique($merge);
    return array_values($unique);
}

function pathToFile($path)
{
    if (file_exists($path)) {
        return $path;
    }
    throw new \Exception("File '{$path}' not found.");
}

function getFile($path)
{
    return file_get_contents(pathToFile($path));
}

function genDiff($file1, $file2, $format = "pretty")
{
    $formatFile1 = pathinfo($file1, PATHINFO_EXTENSION);
    $formatFile2 = pathinfo($file2, PATHINFO_EXTENSION);
    $data1 = parseToFormat(getFile($file1), $formatFile1);
    $data2 = parseToFormat(getFile($file2), $formatFile2);

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
            if (array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
                if (is_array($data1[$key]) && is_array($data2[$key])) {
                    $acc[] = genAstElement($key, null, null, 'nested', $genDiff($data1[$key], $data2[$key]));
                } elseif ($data1[$key] === $data2[$key]) {
                    $acc[] = genAstElement($key, $data1[$key], $data2[$key], 'not changed');
                } else {
                    $acc[] = genAstElement($key, $data1[$key], $data2[$key], 'changed');
                }
            } elseif (array_key_exists($key, $data1)) {
                if (is_array($data1[$key])) {
                    $acc[] = genAstElement($key, $data1[$key], null, 'removed');
                } else {
                    $acc[] = genAstElement($key, $data1[$key], null, 'removed');
                }
            } else {
                if (is_array($data2[$key])) {
                    $acc[] = genAstElement($key, null, $data2[$key], 'added');
                } else {
                    $acc[] = genAstElement($key, null, $data2[$key], 'added');
                }
            }
            return $acc;
        }, []);
        return $result;
    };
    return $genDiff($data1, $data2);
}
