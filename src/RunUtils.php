<?php

namespace Differ\RunUtils;

use Differ\GenDiff;

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
    $genDiff = new GenDiff($firstFile, $secondFile, $result["--format"]);
    echo $genDiff;
}
