<?php
/**
 * Date: 25/04/2020
 * Time: 23:36
 */

namespace Tests\Services\Writers;


use App\Services\Readers\CsvReader;
use App\Services\Readers\JsonReader;
use App\Services\Writers\JsonWriter;
use App\Services\Writers\WriterProblemWithArrayEntry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class JsonWriterTest extends TestCase
{
    const DOCS_PATH = __DIR__.'/../../../docs/';

    public function testFileNotFoundWillRaiseException()
    {
        $writer = new JsonWriter();
        $this->expectException(FileNotFoundException::class);
        $writer->write('whatsoever',[]);
    }

    public function testInvalidExtension()
    {
        $writer = new JsonWriter();
        $this->expectException(\InvalidArgumentException::class);
        $writer->write(self::DOCS_PATH.'questions.csv', []);
    }

    public function testInvalidArray()
    {
        $writer = new JsonWriter();
        $this->expectException(WriterProblemWithArrayEntry::class);
        $writer->write(self::DOCS_PATH.'questions.json', ['badArray']);
    }

    public function testCorrectArrayReaded()
    {
        $writer = new JsonWriter();
        $correctJsonFile = file_get_contents(__DIR__.'/new_element.json');
        $arrNewElement = \json_decode($correctJsonFile, true);
        $originalFile = __DIR__.'/questions_beforetest.json';
        $testFile = __DIR__.'/questions.json';
        copy($originalFile, $testFile);
        $writer->write($testFile, $arrNewElement[0]);

        $reader = new JsonReader();
        $dataWritten = $reader->read($testFile);

        $this->assertIsArray($dataWritten);
        $this->assertCount(3, $dataWritten);
        $this->assertEquals('What does mean O.A.T. ?', $dataWritten[1]['text']);
        $this->assertEquals('Open Acknowledgment Technologies', $dataWritten[1]['choices'][2]['text']);
        $this->assertEquals('New Element ?', $dataWritten[2]['text']);
        unlink($testFile);
    }
}
