<?php

namespace Differ\RunUtils;

use function Differ\genDiff;
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
    $handle = \Docopt::handle(DOC);

    $firstFile = $handle["<firstFile>"];
    $secondFile = $handle["<secondFile>"];
    $genDiff = \Differ\genDiff($firstFile, $secondFile, $handle["--format"]);
    print_r($genDiff);
    var_dump($genDiff);

}
