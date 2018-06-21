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
use Test\Result;

class GenDiffTest extends TestCase
{
    public function testGenDiffFlatFileJson()
    {
        $pathFile1 = __DIR__."/fixtures/before.json";
        $pathFile2 = __DIR__."/fixtures/after.json";
        $this->assertEquals(Result\FLAT_FILE, Differ\genDiff($pathFile1, $pathFile2));
    }

    public function testGenDiffRecFileJson()
    {
        $pathFile1 = __DIR__ . "/fixtures/before2.json";
        $pathFile2 = __DIR__ . "/fixtures/after2.json";
        $this->assertEquals(Result\REC_FILE, Differ\genDiff($pathFile1, $pathFile2));
    }
    public function testGenDiffRecFileJsonReportPlain()
    {
        $pathFile1 = __DIR__ . "/fixtures/before2.json";
        $pathFile2 = __DIR__ . "/fixtures/after2.json";
        $this->assertEquals(Result\REC_FILE_PLAIN, Differ\genDiff($pathFile1, $pathFile2, 'plain'));
    }

    public function testGenDiffFlatFileYaml()
    {
        $pathFile1 = __DIR__."/fixtures/before.yml";
        $pathFile2 = __DIR__."/fixtures/after.yml";
        $this->assertEquals(Result\FLAT_FILE, Differ\genDiff($pathFile1, $pathFile2));
    }
}
