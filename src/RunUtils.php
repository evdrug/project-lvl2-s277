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
    \Docopt::handle(DOC);
}
