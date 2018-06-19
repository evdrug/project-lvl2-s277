<?php

namespace Differ;

use Symfony\Component\Yaml\Yaml;

class ParserFiles
{
    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function existsFile($path, $rootDir = null)
    {
        $dirPath = $this->dirPath($path, $rootDir);

        if (file_exists($dirPath)) {
            return $dirPath;
        }

        throw new \Exception("File '{$path}' not found.");
    }

    public function dirPath($path, $rootDir = null)
    {
        $directory = $rootDir ? $rootDir : getcwd();
        if (strpos($path, DIRECTORY_SEPARATOR) === 0) {
            return $path;
        }

        return $directory.DIRECTORY_SEPARATOR."$path";
    }

    public function openFile($path)
    {
        return file_get_contents($path);
    }

    public function getFormatFile($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public function parse($data, $format)
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

    public function getFile()
    {
        $file = $this->openFile($this->existsFile($this->file));
        $format = $this->getFormatFile($this->file);
        return $this->parse($file, $format);
    }
}
