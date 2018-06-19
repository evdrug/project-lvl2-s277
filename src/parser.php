<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function pathToFile($path, $rootDir = null)
{
    $allPath = pathFullTransform($path, $rootDir);

    if (file_exists($allPath)) {
        return $allPath;
    }
    throw new \Exception("File '{$path}' not found.");
}

function pathFullTransform($path, $rootDir = null)
{
    $directory = $rootDir ? $rootDir : getcwd();
    if (strpos($path, DIRECTORY_SEPARATOR) === 0) {
        return $path;
    }

    return $directory.DIRECTORY_SEPARATOR."$path";
}

function openFile($path)
{
    return file_get_contents($path);
}

function getFormatFile($path)
{
    return pathinfo($path, PATHINFO_EXTENSION);
}

function parser($data, $format)
{
    switch ($format) {
        case 'json':
            return json_decode($data, true);
            break;
        case 'yaml':
            return Yaml::parse($data);
            break;
        default:
            throw new \Exception("File format '{$format}' is not supported");
    }
}

function getParseFile($file)
{
    $response = openFile(pathToFile($file));
    $format = getFormatFile($file);
    return parser($response, $format);
}
