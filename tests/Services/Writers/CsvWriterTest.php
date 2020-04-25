<?php
/**
 * Date: 25/04/2020
 * Time: 23:36
 */

namespace Tests\Services\Writers;


use App\Services\Readers\CsvReader;
use App\Services\Readers\ReaderProblemWithFile;
use App\Services\Writers\CsvWriter;
use App\Services\Writers\WriterProblemWithArrayEntry;
use App\Services\Writers\WriterProblemWithFile;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class CsvWriterTest extends TestCase
{
    const DOCS_PATH = __DIR__.'/../../../docs/';
    public function testFileNotFoundWillRaiseException()
    {
        $writer = new CsvWriter();
        $this->expectException(FileNotFoundException::class);
        $writer->write('whatsoever',[]);
    }

    public function testInvalidExtension()
    {
        $writer = new CsvWriter();
        $this->expectException(\InvalidArgumentException::class);
        $writer->write(self::DOCS_PATH.'questions.json', []);
    }

    public function testInvalidArray()
    {
        $writer = new CsvWriter();
        $this->expectException(WriterProblemWithArrayEntry::class);
        $writer->write(self::DOCS_PATH.'questions.csv', ['badArray']);
    }

    public function testCorrectArrayReaded()
    {
        $writer = new CsvWriter();
        $correctJsonFile = file_get_contents(__DIR__.'/new_element.json');
        $arrNewElement = \json_decode($correctJsonFile, true);
        $originalFile = __DIR__.'/questions_beforetest.csv';
        $testFile = __DIR__.'/questions.csv';
        copy($originalFile, $testFile);
        $writer->write($testFile, $arrNewElement[0]);

        $reader = new CsvReader();
        $dataWritten = $reader->read($testFile);

        $this->assertIsArray($dataWritten);
        $this->assertCount(3, $dataWritten);
        $this->assertEquals('What does mean O.A.T. ?', $dataWritten[1]['text']);
        $this->assertEquals('Open Acknowledgment Technologies', $dataWritten[1]['choices'][2]['text']);
        $this->assertEquals('New Element ?', $dataWritten[2]['text']);
        unlink($testFile);
    }
}
