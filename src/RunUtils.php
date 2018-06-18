<?php

namespace Differ\RunUtils;

use Differ\GenDiff;
use Differ\ParserFiles;

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

    $firstFile = new ParserFiles($result["<firstFile>"]);
    $secondFile = new ParserFiles($result["<secondFile>"]);
    new GenDiff($firstFile, $secondFile, $result["--format"]);
}
