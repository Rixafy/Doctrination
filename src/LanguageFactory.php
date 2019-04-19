<?php

declare(strict_types=1);

namespace Rixafy\Language;

class LanguageFactory
{
    public function create(LanguageData $languageData): Language
    {
        return new Language($languageData);
    }
}