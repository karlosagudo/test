<?php

/*
 * This file is part of an exercise by:
 *
 * Karlos Agudo <karlosagudo1978@gmail.com>
 *
 */

namespace App\Services;

use App\Services\Readers\Reader;
use App\Services\Readers\ReaderNotFound;
use Psr\Log\LoggerInterface;

class PersistenceReaderManager
{
    private const NAMESPACE_READERS = 'App\\Services\\Readers\\';
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $classReader;

    public function __construct(LoggerInterface $logger, string $persistence)
    {
        $this->logger = $logger;
        $this->classReader = ucfirst($persistence);
    }

    public function createReader(): Reader //this way we also ensure the interface is the correct
    {
        $this->logger->debug('Received {read} as persistance layer', ['read' => $this->classReader]);
        $readerClass = self::NAMESPACE_READERS.$this->classReader.'Reader';

        if (!class_exists($readerClass)) {
            throw new ReaderNotFound('THe class:'.$readerClass.' has not been found');
        }

        return new $readerClass();
    }
}
