<?php
/**
 * Date: 25/04/2020
 * Time: 11:20
 */

namespace App\Tests\Services\Readers;

use App\Services\Readers\CsvReader;
use App\Services\Readers\JsonReader;
use App\Services\Readers\ReaderProblemWithFile;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class JsonReaderTest extends TestCase
{
    const DOCS_PATH = __DIR__.'/../../../docs/';

    public function testFileNotFoundWillRaiseException()
    {
        $reader = new JsonReader();
        $this->expectException(FileNotFoundException::class);
        $reader->read('whatsoever');
    }

    public function testInvalidExtension()
    {
        $reader = new JsonReader();
        $this->expectException(\InvalidArgumentException::class);
        $reader->read(self::DOCS_PATH.'questions.csv');
    }

    public function testInvalidFile()
    {
        $reader = new JsonReader();
        $this->expectException(ReaderProblemWithFile::class);
        $reader->read(__DIR__. '/incorrect_json.json');
    }

    public function testCorrectArrayReaded()
    {
        $reader = new JsonReader();
        $correct = $reader->read(self::DOCS_PATH.'questions.json');

        $this->assertIsArray($correct);
        $this->assertCount(2, $correct);
        $this->assertEquals('What does mean O.A.T. ?', $correct[1]['text']);
        $this->assertEquals('Open Acknowledgment Technologies', $correct[1]['choices'][2]['text']);
    }
}
