<?php

/*
 * This file is part of an exercise by:
 *
 * Karlos Agudo <karlosagudo1978@gmail.com>
 *
 */

namespace App\Services\Readers;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class JsonReader implements Reader
{
    public function checkFile(string $file)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException('File '.$file.' was not found');
        }

        if ('json' !== pathinfo($file, PATHINFO_EXTENSION)) {
            throw new \InvalidArgumentException('File is not json file:'.$file);
        }
    }

    public function read(string $file): array
    {
        $questionArr = [];
        $this->checkFile($file);

        try {
            $content = file_get_contents($file);
            $questionArr = json_decode($content, true);
            if (0 !== json_last_error()) {
                throw new \Exception();
            }
        } catch (\Exception $exception) {
            throw new ReaderProblemWithFile($file.'Error:'.json_last_error_msg());
        }

        return $questionArr;
    }
}
