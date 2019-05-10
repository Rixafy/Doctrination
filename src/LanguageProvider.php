<?php

declare(strict_types=1);

namespace Rixafy\Language;

use Doctrine\ORM\EntityManagerInterface;
use Rixafy\Language\Exception\LanguageNotFoundException;
use Rixafy\Language\Exception\LanguageNotProvidedException;

class LanguageProvider
{
    /** @var Language */
    private $language;

    /** @var LanguageFacade */
    private $languageFacade;

	/** @var EntityManagerInterface */
	private $entityManager;

    public function __construct(LanguageFacade $languageFacade, EntityManagerInterface $entityManager)
    {
        $this->languageFacade = $languageFacade;
        $this->entityManager = $entityManager;
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
	 * @throws LanguageNotFoundException
	 */
	public function provide(string $languageCode): void
    {
		if (php_sapi_name() === 'cli') {
			if (!$this->entityManager->getConnection()->getSchemaManager()->tablesExist(['language'])) {
				return;
			}
		}

		try {
			$this->language = $this->languageFacade->getByIso($languageCode);
			LanguageStaticHolder::setLanguage($this->language);

		} catch (LanguageNotFoundException $e) {
			if (php_sapi_name() !== 'cli') {
				throw $e;
			}
		}
    }

	/**
	 * @throws LanguageNotFoundException
	 */
	public function change(string $languageCode): void
    {
        $this->provide($languageCode);
    }
}
