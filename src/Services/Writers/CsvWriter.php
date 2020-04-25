<?php

/*
 * This file is part of an exercise by:
 *
 * Karlos Agudo <karlosagudo1978@gmail.com>
 *
 */

namespace App\Services\Writers;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class CsvWriter implements Writer
{
    public function checkFile(string $file): void
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException('File '.$file.' was not found');
        }

        if ('csv' !== pathinfo($file, PATHINFO_EXTENSION)) {
            throw new \InvalidArgumentException('File is not csv file:'.$file);
        }
    }

    public function write(string $filePath, array $newElement): array
    {
        $this->checkFile($filePath);

        if (!isset($newElement['text']) || !isset($newElement['createdAt']) || !isset($newElement['choices'])) {
            throw new WriterProblemWithArrayEntry('Invalid Array passed to be converted');
        }

        $csvArrayLine = [$newElement['text'], $newElement['createdAt']];
        foreach ($newElement['choices'] as $choice) {
            $csvArrayLine[] = $choice['text'];
        }

        try {
            $fileHandler = fopen($filePath, 'a+');
            $this->correctFPutCSV($fileHandler, $csvArrayLine);
        } catch (\Exception $e) {
            throw new WriterProblemWithFile($e->getMessage());
        }

        return $csvArrayLine;
    }

    private function correctFPutCSV($fileHandler, array $line)
    {
        $encodeFunc = function ($value) {
            $value = str_replace('\\"', '"', $value);
            $value = str_replace('"', '\"', $value);

            return '"'.$value.'"';
        };

        fwrite($fileHandler, implode(',', array_map($encodeFunc, $line))."\n");
        fclose($fileHandler);
    }
}
