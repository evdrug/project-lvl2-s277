<?php
namespace Differ\OpenFile;

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
    return file_get_contents(pathToFile($path));
}
