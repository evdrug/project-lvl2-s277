<?php

namespace Differ\Reports;

use function Funct\Collection\flattenAll;

function reportToFormat($data, $format)
{
    $reportFormatMap = [
        'json' => function ($data) {
            return reportJson($data);
        },
        'pretty' => function ($data) {
            return reportPretty($data);
        },
        'plain' => function ($data) {
            return reportPlain($data);
        }
    ];

    if (!empty($reportFormatMap[$format])) {
        return $reportFormatMap[$format]($data);
    } else {
        throw new \Exception("Report format '{$format}' is not supported");
    }
}

function reportJson($data)
{
    return json_encode($data, JSON_FORCE_OBJECT);
}

function normalizeValue($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}

function reportPretty($data)
{
    $reportChild = function ($child) use (&$reportChild) {
        return array_map(function ($item) use ($reportChild, $child) {
            if (is_array($item)) {
                return $reportChild($item);
            } else {
                return "    {$item}: {$child[$item]}";
            }
        }, array_keys($child));
    };


    $report = function ($data, $repit) use (&$report, $reportChild) {
        $indent = function ($position = 0) use ($repit) {
            return str_repeat(' ', $position + 2 + 2 * $repit);
        };
        return array_reduce($data, function ($acc, $item) use ($report, $reportChild, $indent, $repit) {
            [
                'property' => $property,
                'before' => $rawBefore,
                'after' => $rawAfter,
                'action' => $action,
                'children' => $children
            ] = $item;
            $before = normalizeValue($rawBefore);
            $after = normalizeValue($rawAfter);
            switch ($action) {
                case 'nested':
                    $resultChild = $report($children, $repit + 2);
                    $acc[] = "{$indent(2)}{$property}: {";
                    $acc[] = $resultChild;
                    $acc[] = "{$indent(2)}}";
                    break;
                case 'not changed':
                    $acc[] = "{$indent(2)}{$property}: {$before}";
                    break;
                case 'changed':
                    $acc[] = "{$indent()}+ {$property}: {$after}";
                    $acc[] = "{$indent()}- {$property}: {$before}";

                    break;
                case 'removed':
                    if (is_array($before)) {
                        $child = join('', $reportChild($before));
                        $acc[] = "{$indent()}- {$property}: {";
                        $acc[] = "{$indent(2)}{$child}";
                        $acc[] = "{$indent()}  }";
                    } else {
                        $acc[] = "{$indent()}- {$property}: {$before}";
                    }
                    break;
                case 'added':
                    if (is_array($after)) {
                        $child = join('', $reportChild($after));
                        $acc[] = "{$indent()}+ {$property}: {";
                        $acc[] = "{$indent(2)}{$child}";
                        $acc[] = "{$indent()}  }";
                    } else {
                        $acc[] = "{$indent()}+ {$property}: {$after}";
                    }
                    break;
            }
            return $acc;
        }, []);
    } ;
    return "{".PHP_EOL.join(PHP_EOL, flattenAll($report($data, 0))).PHP_EOL."}".PHP_EOL;
}

function reportPlain($data)
{
}
