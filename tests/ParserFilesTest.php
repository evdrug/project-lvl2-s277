<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class ParserFilesTest extends TestCase
{
    protected $parser;
    protected $root;

    public function setUp()
    {
        $this->root = vfsStream::setUp('tmp');
        $file = touch(vfsStream::url('tmp\test1.json'));
        $this->parser = new \Differ\ParserFiles($file);
    }

    public function testDirPath()
    {
        $path1 = DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."q";
        $this->assertEquals($path1, $this->parser->dirPath($path1));
        $this->assertEquals(
            vfsStream::url('tmp/test1.json'),
            $this->parser->dirPath('test1.json', vfsStream::url('tmp'))
        );
    }

    /**
     * @expectedException Exception
     */
    public function testExistsFileError()
    {
        $this->parser->existsFile('test3.json', vfsStream::url('tmp'));
    }

    public function testExistsFile()
    {
        $this->assertNotEquals(
            vfsStream::url('tmp/test1.json'),
            $this->parser->dirPath('../test3.json', vfsStream::url('tmp'))
        );
    }

    public function testParse()
    {
        $json = '{"firstName": "John", "lastName": "Smith"}';
        $array = ['firstName' => 'John', 'lastName' => 'Smith'];
        $this->assertArraySubset($array, $this->parser->parse($json, 'json'));
    }

    /**
    * @expectedException Exception
     */
    public function testParseError()
    {
        $json = '{"firstName": "John", "lastName": "Smith"}';
        $array = ['firstName' => 'Johns', 'lastName' => 'Smith'];
        $this->parser->parse($json, 'doc');
    }
}