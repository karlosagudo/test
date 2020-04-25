<?php

/*
 * This file is part of an exercise by:
 *
 * Karlos Agudo <karlosagudo1978@gmail.com>
 *
 */

namespace App\Services\Readers;

interface Reader
{
    public function read(string $file): array;

    public function checkFile(string $file);
}
