<?php

/*
 * This file is part of an exercise by:
 *
 * Karlos Agudo <karlosagudo1978@gmail.com>
 *
 */

namespace App\Controller\Questions;

use App\Services\PersistenceReaderManager;
use App\Services\Readers\Reader;
use App\Services\Translator\Translator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class QuestionsReader extends AbstractController
{
    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var string
     */
    private $file;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(PersistenceReaderManager $readerManager, string $fileToRead, Translator $translator, LoggerInterface $logger)
    {
        $this->reader = $readerManager->createReader();
        $this->file = $fileToRead;
        $this->logger = $logger;
        $this->translator = $translator;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $lang = $this->getLangFromRequest($request);

        $this->logger->debug('Reading File:'.$this->file);
        $questions = $this->reader->read($this->file);
        if ('en' !== $lang) {
            $questions = $this->translator->translateTexts($lang, $questions);
        }

        return new JsonResponse($questions);
    }

    private function getLangFromRequest(Request $request): string
    {
        $lang = $request->get('lang', null);
        if (!preg_match('/^\w{2}$/', $lang)) {
            throw new InvalidArgumentException('Lang must be 2 characters long');
        }

        $this->logger->debug('Language Selected:'.$lang);

        return $lang;
    }
}
