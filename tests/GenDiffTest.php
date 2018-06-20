<?php
/**
 * Created by PhpStorm.
 * User: E.Druzyakin
 * Date: 20.06.18
 * Time: 17:09
 */

namespace Test;

use Differ;
use PHPUnit\Framework\TestCase;
use function Differ\Parser\parseToFormat;

class GenDiffTest extends TestCase
{
    const FLAT_FILE = <<<TEXT
{
    host: hexlet.io
  + timeout: 20
  - timeout: 50
  - proxy: 123.234.53.22
  + verbose: true
}

TEXT;

    public function testGenDiffAstFlat()
    {
        $ast = [
            [
                'property' => 'host',
                'before' => 'hexlet.io',
                'after' => 'hexlet.io',
                'action' => 'not changed',
                'children' => null
            ],
            [
                'property' => 'timeout',
                'before' => 50,
                'after' => 20,
                'action' => 'changed',
                'children' => null
            ],
            [
                'property' => 'proxy',
                'before' => '123.234.53.22',
                'after' => null,
                'action' => 'removed',
                'children' =>null
            ],
            [
                'property' => 'verbose',
                'before' => null,
                'after' => 'true',
                'action' => 'added',
                'children' => null
            ]
        ];
        $pathFile1 = __DIR__."/../src/dataFiles/before.json";
        $pathFile2 = __DIR__."/../src/dataFiles/after.json";
        $data1 = parseToFormat($pathFile1, 'json');
        $data2 = parseToFormat($pathFile2, 'json');
        $this->assertEquals($ast, Differ\genDiffAst($data1, $data2));
    }

    public function testGenDiffFlatFileJson()
    {
        $pathFile1 = __DIR__."/../src/dataFiles/before.json";
        $pathFile2 = __DIR__."/../src/dataFiles/after.json";
        $this->assertEquals(self::FLAT_FILE, Differ\genDiff($pathFile1, $pathFile2));
    }
    public function testGenDiffFlatFileYaml()
    {
        $pathFile1 = __DIR__."/../src/dataFiles/before.yml";
        $pathFile2 = __DIR__."/../src/dataFiles/after.yml";
        $this->assertEquals(self::FLAT_FILE, Differ\genDiff($pathFile1, $pathFile2));
    }
}
