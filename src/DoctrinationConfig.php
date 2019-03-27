<?php

declare(strict_types=1);

namespace Rixafy\Doctrination;

use Rixafy\Doctrination\Language\Exception\LanguageNotFoundException;
use Rixafy\Doctrination\Language\LanguageProvider;

class DoctrinationConfig
{
    /** @var LanguageProvider */
    private $languageProvider;

    private function __construct(LanguageProvider $languageProvider)
    {
        $this->languageProvider = $languageProvider;
    }

    /**
     * @param string $isoCode
     * @throws LanguageNotFoundException
     */
    public function setLanguage(string $isoCode): void
    {
        $this->languageProvider->provide($isoCode);
    }
}