<?php

declare(strict_types=1);

namespace Rixafy\Language\Language;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Language\Exception\LanguageNotFoundException;

class LanguageFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var LanguageRepository */
    private $languageRepository;

    /** @var LanguageFactory */
    private $languageFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        LanguageRepository $languageRepository,
        LanguageFactory $languageFactory
    ) {
        $this->entityManager = $entityManager;
        $this->languageRepository = $languageRepository;
        $this->languageFactory = $languageFactory;
    }

    public function create(LanguageData $languageData): Language
    {
        $language = $this->languageFactory->create($languageData);

        $this->entityManager->persist($language);
        $this->entityManager->flush();

        return $language;
    }

    public function edit(UuidInterface $id, LanguageData $languageData): Language
    {
        $language = $this->languageRepository->get($id);
        $language->edit($languageData);

        $this->entityManager->flush();

        return $language;
    }

	/**
	 * @throws LanguageNotFoundException
	 */
	public function remove(UuidInterface $id): void
    {
        $language = $this->languageRepository->get($id);
        $this->entityManager->remove($language);

        $this->entityManager->flush();
    }

	/**
	 * @throws LanguageNotFoundException
	 */
	public function get(UuidInterface $id): Language
    {
        return $this->languageRepository->get($id);
    }

	/**
	 * @throws LanguageNotFoundException
	 */
	public function getByIso(string $iso): Language
    {
        return $this->languageRepository->getByIso($iso);
    }
}