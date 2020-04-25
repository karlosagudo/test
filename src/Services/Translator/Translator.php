<?php

/*
 * This file is part of an exercise by:
 *
 * Karlos Agudo <karlosagudo1978@gmail.com>
 *
 */

namespace App\Services\Translator;

use Stichoza\GoogleTranslate\GoogleTranslate;

class Translator
{
    public function translateTexts(string $language, array $questions): array
    {
        $translator = new GoogleTranslate($language);
        array_walk_recursive($questions, function (&$element) use ($translator) {
            if (!\is_string($element)) {
                return true;
            }

            $element = $translator->translate($element);
        });

        return $questions;
    }
}
