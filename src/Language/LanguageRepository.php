<?php

declare(strict_types=1);

namespace Rixafy\Doctrination\Language;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Doctrination\Language\Exception\LanguageNotFoundException;

class LanguageRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(Language::class);
    }

    /**
     * @param UuidInterface $id
     * @return Language
     * @throws LanguageNotFoundException
     */
    public function get(UuidInterface $id): Language
    {
        /** @var Language $language */
        $language = $this->getRepository()->findOneBy([
            'id' => $id
        ]);

        if ($language === null) {
            throw new LanguageNotFoundException('Language with id ' . $id . ' not found.');
        }

        return $language;
    }

    /**
     * @param string $iso
     * @return Language
     * @throws LanguageNotFoundException
     */
    public function getByIso(string $iso): Language
    {
        /** @var Language $language */
        $language = $this->getRepository()->findOneBy([
            'iso' => $iso
        ]);

        if ($language === null) {
            throw new LanguageNotFoundException('Language with iso code ' . $iso . ' not found.');
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