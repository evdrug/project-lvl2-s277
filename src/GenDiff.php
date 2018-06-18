<?php

namespace Differ;

use Differ\ParserFiles;

class GenDiff
{
    private $firstFile;
    private $secondFile;
    private $format;

    public function __construct($firstFile, $secondFile, $format)
    {
        $this->firstFile = new ParserFiles($firstFile);
        $this->secondFile = new ParserFiles($secondFile);
        $this->format = $format;
    }

    public function getKeys($data1, $data2)
    {
        $merge = array_merge(array_keys($data1), array_keys($data2));
        $unique = array_unique($merge);
        return array_values($unique);
    }

    public function diffFiles($data1, $data2)
    {
        $keys = $this->getKeys($data1, $data2);
        $result = array_reduce($keys, function ($acc, $key) use ($data1, $data2) {
            if (array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
                if ($data1[$key] === $data2[$key]) {
                     $acc[] = "  {$key}: {$data2[$key]}";
                } else {
                    $acc[] = "- {$key}: {$data1[$key]}";
                    $acc[] = "+ {$key}: {$data2[$key]}";
                }
            } elseif (array_key_exists($key, $data1)) {
                $acc[] = "- {$key}: {$data1[$key]}";
            } else {
                $acc[] = "+ {$key}: {$data2[$key]}";
            }

            return $acc;
        }, []);

        return $result;
    }

    public function getInfo()
    {
        return $this->diffFiles($this->firstFile->getFile(), $this->secondFile->getFile());
    }
}
