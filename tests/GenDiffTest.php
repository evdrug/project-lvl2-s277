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


//    public function testGenDiffFlatFile()
//    {
//        $pathFile1 = __DIR__."/../src/dataFiles/before.json";
//        $pathFile2 = __DIR__."/../src/dataFiles/after.json";
//        $this->assertEquals(self::FLAT_FILE, Differ\genDiff($pathFile1, $pathFile2));
//    }

}
