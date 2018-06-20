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

function genDiff($file1, $file2, $format = "pretty")
{


    $formatFile1 = pathinfo($file1, PATHINFO_EXTENSION);
    $formatFile2 = pathinfo($file2, PATHINFO_EXTENSION);
    $data1 = parseToFormat($file1, $formatFile1);
    $data2 = parseToFormat($file2, $formatFile2);

//    genDiffAst($data1, $data2);
    return reportToFormat('{"sss":2}', $format);
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

function genDiffAst($data1, $data2)
{
    $keys = getKeys($data1, $data2);
    $result = array_reduce($keys, function ($acc, $key) use ($data1, $data2) {

        return $acc;
    }, []);

    var_dump($result);
}
