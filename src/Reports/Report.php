<?php

namespace Differ\Reports;

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

function normalizeValue($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}
