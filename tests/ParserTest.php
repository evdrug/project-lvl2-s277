<?php

namespace Test;

use function Differ\Parser\parseToFormat;
use \PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /**
     * @expectedException Exception
     */
    public function testParseToFormatError()
    {
        parseToFormat('txt', '');
    }

//    public function testParseToFormatJson()
//    {
//        $result = ["host" => "hexlet.io", "timeout" => 50, "proxy" => "123.234.53.22"];
//        $pathFile = __DIR__."/../src/dataFiles/before.json";
//        $data = file_get_contents($pathFile);
//        $this->assertArraySubset($result, parseToFormat('json', $data));
//    }
//
//    public function testParseToFormatYml()
//    {
//        $result = ["host" => "hexlet.io", "timeout" => 50, "proxy" => "123.234.53.22"];
//        $pathFile = __DIR__."/../src/dataFiles/before.yml";
//        $data = file_get_contents($pathFile);
//        $this->assertArraySubset($result, parseToFormat('yml', $data));
//    }
}
