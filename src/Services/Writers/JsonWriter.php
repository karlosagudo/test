<?php

/*
 * This file is part of an exercise by:
 *
 * Karlos Agudo <karlosagudo1978@gmail.com>
 *
 */

namespace App\Services\Writers;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class JsonWriter implements Writer
{
    public function checkFile(string $file): void
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException('File '.$file.' was not found');
        }

        if ('json' !== pathinfo($file, PATHINFO_EXTENSION)) {
            throw new \InvalidArgumentException('File is not json file:'.$file);
        }
    }

    public function write(string $filePath, array $newElement): array
    {
        $this->checkFile($filePath);

        if (!isset($newElement['text']) || !isset($newElement['createdAt']) || !isset($newElement['choices'])) {
            throw new WriterProblemWithArrayEntry('Invalid Array passed to be converted');
        }

        try {
            $fileContents = file_get_contents($filePath);
            $totalJsonArray = json_decode($fileContents, true);
            $totalJsonArray[] = $newElement;
            file_put_contents($filePath, json_encode($totalJsonArray));
        } catch (\Exception $e) {
            throw new WriterProblemWithFile($e->getMessage());
        }

        return $newElement;
    }

    private function correctFPutCSV($fileHandler, array $line)
    {
        $encodeFunc = function ($value) {
            $value = str_replace('\\"', '"', $value);
            $value = str_replace('"', '\"', $value);

            return '"'.$value.'"';
        };

        fwrite($fileHandler, implode(',', array_map($encodeFunc, $line)));
        fclose($fileHandler);
    }
}
