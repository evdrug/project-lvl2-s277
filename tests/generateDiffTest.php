<?php
namespace Test;

use function Differ\GenerateDiff\diffFiles;
use function Differ\GenerateDiff\genAst;
use function Differ\GenerateDiff\getKeys;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Differ\GenDiff;

class generateDiffTest extends TestCase
{
//    public $parser;
//    public $diff;
    public $data1 = ['a' => 1, 'b' => 2, 'c' => 3];
    public $data2  = ['a' => 1, 'd' => 4, 'c' => 66, 'f' => 5];
    protected $root;
    protected $file1;
    protected $file2;

    public function setUp()
    {
        $this->root = vfsStream::setUp('tmp');
        vfsStream::newFile('file1.json')->at($this->root)->setContent(json_encode($this->data1));
        vfsStream::newFile('file2.json')->at($this->root)->setContent(json_encode($this->data2));
    }

    public function testGetKeys()
    {
        $result = ['a', 'b', 'c', 'd', 'f'];
        $this->assertArraySubset($result, getKeys($this->data1, $this->data2));
    }

    public function testGenAst()
    {
        $result = [
            ['property' => "a", 'before' => 1, 'after' => 1, 'action' => 'not changed'],
            ['property' => "b", 'before' => 2, 'after' => null, 'action' => 'removed'],
            ['property' => "c", 'before' => 3, 'after' => 66, 'action' => 'changed'],
            ['property' => "d", 'before' => null, 'after' => 4, 'action' => 'added'],
            ['property' => "f", 'before' => null, 'after' => 5, 'action' => 'added']

        ];
        $this->assertArraySubset($result, genAst($this->data1, $this->data2));
    }
}
