<?php
namespace Differ;

use function Differ\Parser\getParseFile;
use function Differ\Reports\jsonReport;
use function Differ\Reports\plainReport;
use function Differ\Reports\prettyReport;

function getKeys($data1, $data2)
{
    $merge = array_merge(array_keys($data1), array_keys($data2));
    $unique = array_unique($merge);
    return array_values($unique);
}

function genDiff($file1, $file2, $format = 'pretty')
{

    $data1 = getParseFile($file1);
    $data2 = getParseFile($file2);
    $ast = genAst($data1, $data2);
    return formatReport($ast, $format);
}

function genAst($data1, $data2)
{

    $keys = getKeys($data1, $data2);
    $genData = function ($key, $dataBefore, $dataAfter, $action) {
        return [
            'property' => $key,
            'before' => $dataBefore,
            'after' => $dataAfter,
            'action' => $action
        ];
    };

    $result = array_reduce($keys, function ($acc, $key) use ($data1, $data2, $genData) {

        if (array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
            if (is_array($data1[$key]) && is_array($data2[$key])) {
                $acc[] = [
                    'action' => 'nested',
                    'property' => $key,
                    'children' => genAst($data1[$key], $data2[$key])
                ];
            } elseif ($data1[$key] === $data2[$key]) {
                $acc[] = $genData($key, $data1[$key], $data2[$key], 'not changed');
            } else {
                $acc[] = $genData($key, $data1[$key], $data2[$key], 'changed');
            }
        } elseif (array_key_exists($key, $data1)) {
            $acc[] = $genData($key, $data1[$key], null, 'removed');
        } else {
            $acc[] = $genData($key, null, $data2[$key], 'added');
        }
        return $acc;
    }, []);

    return $result;
}

function formatReport($data, $format)
{
    switch ($format) {
        case 'json':
            return jsonReport($data);
        case 'pretty':
            return prettyReport($data);
        case 'plain':
            return plainReport($data);
        default:
            throw new \Exception("Report format '{$format}' is not supported");
    }
}
