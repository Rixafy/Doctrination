<?php

declare(strict_types=1);

namespace Rixafy\Language;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Language\Exception\LanguageNotFoundException;

class LanguageRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function getRepository()
    {
        return $this->entityManager->getRepository(Language::class);
    }

    /**
     * @throws LanguageNotFoundException
     */
    public function get(UuidInterface $id): Language
    {
        /** @var Language $language */
        $language = $this->getRepository()->findOneBy([
            'id' => $id
        ]);

        if ($language === null) {
            throw LanguageNotFoundException::byId($id);
        }

        return $language;
    }

    /**
     * @throws LanguageNotFoundException
     */
    public function getByIso(string $iso): Language
    {
        /** @var Language $language */
        $language = $this->getRepository()->findOneBy([
            'iso' => $iso
        ]);

        if ($language === null) {
            throw LanguageNotFoundException::byIso($iso);
        }

        return $language;
    }

    public function getCount(): int
    {
        return (int) $this->getQueryBuilderForAll()->getQuery()->getMaxResults();
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('l');
    }
}
