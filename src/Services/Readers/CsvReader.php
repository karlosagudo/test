<?php

/*
 * This file is part of an exercise by:
 *
 * Karlos Agudo <karlosagudo1978@gmail.com>
 *
 */

namespace App\Services\Readers;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class CsvReader implements Reader
{
    const MINIMUM_FIELDS = 3; //question, date, choice

    public function checkFile(string $file): void
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException('File '.$file.' was not found');
        }

        if ('csv' !== pathinfo($file, PATHINFO_EXTENSION)) {
            throw new \InvalidArgumentException('File is not csv file:'.$file);
        }
    }

    public function read(string $file): array
    {
        $questionArr = [];
        $this->checkFile($file);

        try {
            if (false !== ($handle = fopen($file, 'r'))) {
                $lineNum = 1;
                while (false !== ($line = fgetcsv($handle, 1000, ','))) {
                    if (1 === $lineNum) {
                        ++$lineNum;

                        continue;
                    }
                    $questionArr[] = $this->processLine($line, $file, $lineNum);
                    ++$lineNum;
                }
                fclose($handle);
            }
        } catch (\Exception $exception) {
            throw new ReaderProblemWithFile($file);
        }

        return $questionArr;
    }

    /**
     * @param int $lineNum We pass line number too help futuree debugs
     *
     * @throws ReaderProblemWithFile
     */
    private function processLine(array $dataArr, string $file, int $lineNum): array
    {
        $questionArr = [];

        if (\count($dataArr) < self::MINIMUM_FIELDS) {
            throw new ReaderProblemWithFile('File:'.$file.' line:'.$lineNum);
        }

        try {
            $questionArr['text'] = $dataArr[0];
            $questionArr['createdAt'] = (new \DateTime($dataArr[1]))->format('Y-m-d H:i:s');
            $dataCount = \count($dataArr);
            for ($i = 2; $i < $dataCount; ++$i) {
                $questionArr['choices'][] = ['text' => $dataArr[$i]];
            }
        } catch (\Exception $e) { //bad datetime, we dont process
            throw new ReaderProblemWithFile('File:'.$file.' line:'.$lineNum.' check DateTime at column 2');
        }

        return $questionArr;
    }
}
