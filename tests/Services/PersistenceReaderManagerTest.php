<?php
/**
 * Date: 25/04/2020
 * Time: 10:20
 */

namespace App\Tests\Services;

use App\Services\PersistenceReaderManager;
use App\Services\Readers\CsvReader;
use App\Services\Readers\ReaderNotFound;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class PersistenceReaderManagerTest extends TestCase
{
    /** @var NullLogger */
    private $logger;

    public function setUp()
    {
        $this->logger = new NullLogger();
    }

    public function testReaderNotFoundException()
    {
        $badManager = new PersistenceReaderManager($this->logger, 'NotARealOne');
        $this->expectException(ReaderNotFound::class);
        $badManager->createReader();
    }

    public function testACsvReadIsCreated()
    {
        $readManager = new PersistenceReaderManager($this->logger, 'Csv');
        $readerGenerated = $readManager->createReader();
        $this->assertInstanceOf(CsvReader::class, $readerGenerated);
    }
}
