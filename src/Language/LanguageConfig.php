<?php

declare(strict_types=1);

namespace Rixafy\Language;

use Rixafy\Language\Exception\LanguageNotFoundException;
use Rixafy\Language\Language\LanguageProvider;

class LanguageConfig
{
    /** @var LanguageProvider */
    private $languageProvider;

    private function __construct(LanguageProvider $languageProvider)
    {
        $this->languageProvider = $languageProvider;
    }

    /**
     * @throws LanguageNotFoundException
     */
    public function setLanguage(string $isoCode): void
    {
        $this->languageProvider->provide($isoCode);
    }
}