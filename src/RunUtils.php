<?php

namespace Differ\RunUtils;

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
    $pathToFile1 = $handle['<firstFile>'];
    $pathToFile2 = $handle['<secondFile>'];
    $diff = \Differ\genDiff($pathToFile1, $pathToFile2, $handle['--format']);
    print_r($diff);
}
