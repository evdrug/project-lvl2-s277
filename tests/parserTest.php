<?php

namespace Test;

use function Differ\Parser\parser;
use function Differ\Parser\pathFullTransform;
use function Differ\Parser\pathToFile;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class parserTest extends TestCase
{
    protected $root;

    public function setUp()
    {
        $this->root = vfsStream::setUp('tmp');
    }

    public function testPathFullTransform()
    {
        $path1 = DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."q";
        $this->assertEquals($path1, pathFullTransform($path1));
        $this->assertEquals(
            vfsStream::url('tmp/test1.json'),
            pathFullTransform('test1.json', vfsStream::url('tmp'))
        );
    }

    /**
     * @expectedException Exception
     */
    public function testPathToFileError()
    {
        pathToFile('test3.json', vfsStream::url('tmp'));
    }

    public function testPathToFile()
    {
        $this->assertNotEquals(
            vfsStream::url('tmp/test1.json'),
            pathFullTransform('../test3.json', vfsStream::url('tmp'))
        );
    }

    public function testParser()
    {
        $json = '{"firstName": "John", "lastName": "Smith"}';
        $array = ['firstName' => 'John', 'lastName' => 'Smith'];
        $this->assertArraySubset($array, parser($json, 'json'));
    }

    /**
    * @expectedException Exception
     */
    public function testParserError()
    {
        $json = '{"firstName": "John", "lastName": "Smith"}';
        parser($json, 'doc');
    }
}