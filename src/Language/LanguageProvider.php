<?php

declare(strict_types=1);

namespace Rixafy\Language\Language;

use Rixafy\Language\Exception\LanguageNotProvidedException;

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
     * @throws LanguageNotProvidedException
     */
    public function getLanguage(): Language
    {
        if ($this->language === null) {
            throw LanguageNotProvidedException::neverSet();
        }
        return $this->language;
    }

    /**
     * @throws Exception\LanguageNotFoundException
     */
    public function provide(string $languageCode): void
    {
        $this->language = $this->languageFacade->getByIso($languageCode);
        LanguageStaticHolder::setLanguage($this->language);
    }

    /**
     * @throws Exception\LanguageNotFoundException
     */
    public function change(string $languageCode): void
    {
        $this->provide($languageCode);
    }
}