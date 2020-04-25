<?php
/**
 * Date: 25/04/2020
 * Time: 10:20
 */

namespace App\Tests\Services;

use App\Services\PersistenceReaderManager;
use App\Services\PersistenceWriterManager;
use App\Services\Readers\CsvReader;
use App\Services\Readers\ReaderNotFound;
use App\Services\Writers\CsvWriter;
use App\Services\Writers\WriterNotFound;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class PersistenceWriterManagerTest extends TestCase
{
    /** @var NullLogger */
    private $logger;

    public function setUp()
    {
        $this->logger = new NullLogger();
    }

    public function testWriterNotFoundException()
    {
        $badManager = new PersistenceWriterManager($this->logger, 'NotARealOne');
        $this->expectException(WriterNotFound::class);
        $badManager->createWriter();
    }

    public function testACsvReadIsCreated()
    {
        $readManager = new PersistenceWriterManager($this->logger, 'Csv');
        $writer = $readManager->createWriter();
        $this->assertInstanceOf(CsvWriter::class, $writer);
    }
}
