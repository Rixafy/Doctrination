<?php

declare(strict_types=1);

namespace Rixafy\Doctrination\Language;

use Doctrine\ORM\EntityManagerInterface;

class LanguageFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var LanguageRepository */
    private $languageRepository;

    /** @var LanguageFactory */
    private $languageFactory;

    /**
     * LanguageFacade constructor.
     * @param EntityManagerInterface $entityManager
     * @param LanguageRepository $languageRepository
     * @param LanguageFactory $languageFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LanguageRepository $languageRepository,
        LanguageFactory $languageFactory
    ) {
        $this->entityManager = $entityManager;
        $this->languageRepository = $languageRepository;
        $this->languageFactory = $languageFactory;
    }

    /**
     * @param LanguageData $languageData
     * @return Language
     */
    public function create(LanguageData $languageData): Language
    {
        $language = $this->languageFactory->create($languageData);

        $this->entityManager->persist($language);
        $this->entityManager->flush();

        return $language;
    }

    /**
     * @param string $id
     * @param LanguageData $languageData
     * @return Language
     * @throws Exception\LanguageNotFoundException
     */
    public function edit(string $id, LanguageData $languageData): Language
    {
        $language = $this->languageRepository->get($id);
        $language->edit($languageData);

        $this->entityManager->flush();

        return $language;
    }

    /**
     * @param string $id
     * @throws Exception\LanguageNotFoundException
     */
    public function remove(string $id): void
    {
        $language = $this->languageRepository->get($id);
        $this->entityManager->remove($language);

        $this->entityManager->flush();
    }

    /**
     * @param string $id
     * @return Language
     * @throws Exception\LanguageNotFoundException
     */
    public function get(string $id): Language
    {
        return $this->languageRepository->get($id);
    }

    /**
     * @param string $iso
     * @return Language
     * @throws Exception\LanguageNotFoundException
     */
    public function getByIso(string $iso): Language
    {
        return $this->languageRepository->getByIso($iso);
    }
}