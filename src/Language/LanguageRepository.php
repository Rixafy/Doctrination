<?php

declare(strict_types=1);

namespace Rixafy\Doctrination\Language;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
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
     * @param string $id
     * @return Language
     * @throws LanguageNotFoundException
     */
    public function get(string $id): Language
    {
        $language = $this->find($id);

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
        $language = $this->findByIso($iso);

        if ($language === null) {
            throw new LanguageNotFoundException('Language with id ' . $iso . ' not found.');
        }

        return $language;
    }

    public function getCount(): int
    {
        return (int) $this->getQueryBuilderForAll()->getQuery()->getMaxResults();
    }

    public function find(string $id): ?Language
    {
        return $this->getQueryBuilderForAll()
            ->andWhere('l.id = :id')->setParameter('id', Uuid::fromString($id))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByIso(string $iso): ?Language
    {
        return $this->getQueryBuilderForAll()
            ->andWhere('l.iso = :iso')->setParameter('iso', $iso)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('l');
    }
}