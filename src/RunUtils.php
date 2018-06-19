<?php

namespace Differ\RunUtils;

use function Differ\GenerateDiff\diffFiles;
use Funct\Collection;

const DOC = <<<'DOCOPT'
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  --format <fmt>                Report format [default: pretty]
DOCOPT;

function start()
{
    $result = \Docopt::handle(DOC);

    $firstFile = $result["<firstFile>"];
    $secondFile = $result["<secondFile>"];
    $genDiff =  diffFiles($firstFile, $secondFile, $result["--format"]);
    print_r($genDiff);

}
