<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use Differ\GenDiff;

class GenDiffTest extends TestCase
{
    public $parser;
    public $diff;
    public $data1 = ['a' => 1, 'b' => 2, 'c' => 3];
    public $data2  = ['a' => 1, 'd' => 4, 'c' => 66, 'f' => 5];
    public function setUp()
    {
        $this->parser = $this->getMockBuilder('ParserFiles')
            ->setMethods(['getFile'])
            ->getMock();

        $file1 = "";
        $file2 = "";
        $format = "";
        $this->diff = new GenDiff($file1, $file2, $format);
    }

    public function testGetKeys()
    {
        $result = ['a', 'b', 'c', 'd', 'f'];
        $this->assertArraySubset($result, $this->diff->getKeys($this->data1, $this->data2));
    }

    public function testDiffFiles()
    {
        $result = ["  a: 1", "- b: 2", "- c: 3", "+ c: 66", "+ d: 4", "+ f: 5"];
        $this->assertArraySubset($result, $this->diff->diffFiles($this->data1, $this->data2));
    }
}
