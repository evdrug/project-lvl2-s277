<?php

namespace Differ\Reports;

use function Funct\Collection\flatten;
use function Funct\Collection\flattenAll;
use PHP_CodeSniffer\Tokenizers\PHP;

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

function reportPretty($data)
{

    $report = array_reduce($data, function ($acc, $item) {

        [
            'property' => $property,
            'before' => $before,
            'after' => $after,
            'action' => $action,
            'children' => $children
        ] = $item;

        switch ($action) {
            case 'not changed':
                $acc[] =  "    $property: $before";
                break;
            case 'removed':
                $acc[] = "  - $property: $before";
                break;
            case 'added':
                $acc[] = "  + $property: $after";
                break;
            case 'changed':
                $acc[] = "  + $property: $after";
                $acc[] = "  - $property: $before";
                break;
        }
        return $acc;
    }, []);
    return "{".PHP_EOL.join(PHP_EOL, $report).PHP_EOL."}".PHP_EOL;
}

function reportPlain($data)
{
}
