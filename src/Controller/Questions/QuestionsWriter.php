<?php

/*
 * This file is part of an exercise by:
 *
 * Karlos Agudo <karlosagudo1978@gmail.com>
 *
 */

namespace App\Controller\Questions;

use App\Services\PersistenceWriterManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class QuestionsWriter extends AbstractController
{
    /**
     * @var \App\Services\Writers\Writer
     */
    private $writer;
    /**
     * @var string
     */
    private $file;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(PersistenceWriterManager $writerManager, string $fileToRead, LoggerInterface $logger)
    {
        $this->writer = $writerManager->createWriter();
        $this->file = $fileToRead;
        $this->logger = $logger;
    }

    public function __invoke(Request $request)
    {
        $data = $this->checkRequest($request);

        if (false === $request->server->get('test', false)) { //avoid writing in test mode
            $this->writer->write($this->file, $data);
        }

        return new JsonResponse($data);
    }

    private function checkRequest(Request $request): array
    {
        if (0 !== strpos($request->headers->get('Content-Type'), 'application/json')) {
            throw new BadRequestHttpException('Only Application/Json content type');
        }

        $data = json_decode($request->getContent(), true);
        if (!\is_array($data)) {
            throw new BadRequestHttpException('Incorrect Json');
        }
        if (3 !== \count($data['choices'])) {
            throw new BadRequestHttpException('Choices must be 3 elements');
        }

        return $data;
    }
}
