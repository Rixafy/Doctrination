<?php

declare(strict_types=1);

namespace Rixafy\Doctrination\Language;

use Rixafy\Doctrination\Language\Exception\LanguageNotProvidedException;

class LanguageProvider
{
    /** @var Language */
    private $language;

    /** @var LanguageFacade */
    private $languageFacade;

    public function __construct(LanguageFacade $languageFacade)
    {
        $this->languageFacade = $languageFacade;
    }

    /**
     * @return Language
     * @throws LanguageNotProvidedException
     */
    public function getLanguage(): Language
    {
        if ($this->language === null) {
            throw new LanguageNotProvidedException('Language was not provided and default language is missing.');
        }
        return $this->language;
    }

    /**
     * @param string $languageCode
     * @throws Exception\LanguageNotFoundException
     */
    public function provide(string $languageCode): void
    {
        $this->language = $this->languageFacade->getByIso($languageCode);
        LanguageHolder::setLanguage($this->language);
    }

    /**
     * @param string $languageCode
     * @throws Exception\LanguageNotFoundException
     */
    public function change(string $languageCode): void
    {
        $this->provide($languageCode);
    }
}