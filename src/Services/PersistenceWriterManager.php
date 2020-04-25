<?php

/*
 * This file is part of an exercise by:
 *
 * Karlos Agudo <karlosagudo1978@gmail.com>
 *
 */

namespace App\Services;

use App\Services\Writers\Writer;
use App\Services\Writers\WriterNotFound;
use Psr\Log\LoggerInterface;

class PersistenceWriterManager
{
    private const NAMESPACE_READERS = 'App\\Services\\Writers\\';
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $classWriter;

    public function __construct(LoggerInterface $logger, string $persistence)
    {
        $this->logger = $logger;
        $this->classWriter = ucfirst($persistence);
    }

    public function createWriter(): Writer //this way we also ensure the interface is the correct
    {
        $this->logger->debug('Received {writer} as persistance layer', ['writer' => $this->classWriter]);
        $writerClass = self::NAMESPACE_READERS.$this->classWriter.'Writer';

        if (!class_exists($writerClass)) {
            throw new WriterNotFound('THe class:'.$writerClass.' has not been found');
        }

        return new $writerClass();
    }
}
