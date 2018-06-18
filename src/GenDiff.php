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
        $this->firstFile = $firstFile;
        $this->secondFile = $secondFile;
        $this->format = $format;

        var_dump($firstFile->getFile());
        var_dump($secondFile->getFile());
    }


}
