<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function existsFile($path, $rootDir = null)
{
    $allPath = dirPath($path, $rootDir);

    if (file_exists($allPath)) {
        return $allPath;
    }
    throw new \Exception("File '{$path}' not found.");
}

function dirPath($path, $rootDir = null)
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

function parse($data, $format)
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

function getFile($file)
{
    $response = openFile(existsFile($file));
    $format = getFormatFile($file);
    return parse($response, $format);
}
