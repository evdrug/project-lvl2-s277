<?php

namespace Test;

use function Differ\Parser\dirPath;
use function Differ\Parser\existsFile;
use function Differ\Parser\parse;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class parserTest extends TestCase
{
    protected $root;

    public function setUp()
    {
        $this->root = vfsStream::setUp('tmp');
    }

    public function testDirPath()
    {
        $path1 = DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."q";
        $this->assertEquals($path1, dirPath($path1));
        $this->assertEquals(
            vfsStream::url('tmp/test1.json'),
            dirPath('test1.json', vfsStream::url('tmp'))
        );
    }

    /**
     * @expectedException Exception
     */
    public function testExistsFileError()
    {
        existsFile('test3.json', vfsStream::url('tmp'));
    }

    public function testExistsFile()
    {
        $this->assertNotEquals(
            vfsStream::url('tmp/test1.json'),
            dirPath('../test3.json', vfsStream::url('tmp'))
        );
    }

    public function testParse()
    {
        $json = '{"firstName": "John", "lastName": "Smith"}';
        $array = ['firstName' => 'John', 'lastName' => 'Smith'];
        $this->assertArraySubset($array, parse($json, 'json'));
    }

    /**
    * @expectedException Exception
     */
    public function testParseError()
    {
        $json = '{"firstName": "John", "lastName": "Smith"}';
        parse($json, 'doc');
    }
}