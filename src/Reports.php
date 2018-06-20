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

function reportJson($data)
{
    return json_encode($data, JSON_FORCE_OBJECT);
}

function reportPretty($data)
{
}

function reportPlain($data)
{
}
