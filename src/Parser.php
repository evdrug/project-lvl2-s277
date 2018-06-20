<?php

namespace Differ\Parser;

use function Differ\OpenFile\openFile;
use Symfony\Component\Yaml\Yaml;

function parseToFormat($file, $format)
{
    $data = openFile($file);

    $formatMapp = [
        "json" => function ($data) {
            return jsonParser($data);
        },
        "yml" => function ($data) {
            return ymlParser($data);
        }
    ];

    if (!empty($formatMapp[$format])) {
        return $formatMapp[$format]($data);
    } else {
        throw new \Exception("File format '{$format}' is not supported");
    }
}

function jsonParser($data)
{
    return json_decode($data, true);
}

function ymlParser($data)
{
    return Yaml::parse($data);
}
