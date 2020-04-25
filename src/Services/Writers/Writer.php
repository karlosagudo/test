<?php

/*
 * This file is part of an exercise by:
 *
 * Karlos Agudo <karlosagudo1978@gmail.com>
 *
 */

namespace App\Services\Writers;

interface Writer
{
    public function checkFile(string $file);

    public function write(string $filePath, array $newElement): array;
}
